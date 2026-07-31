<?php

namespace App\Http\Controllers\API;

use App\Enums\StatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Recharge\CreateRechargeRequest;
use App\Http\Requests\Recharge\UpdateRechargeRequest;
use App\Http\Resources\Recharge\RechargeResource;
use App\Mail\SendEvaluationReportMail;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\Recharge;
use App\Models\Transaction;
use App\Models\Status;
use App\Services\EmailValidationService;
use App\Services\Wave\WaveCheckoutService;
use Carbon\Carbon;
use Essa\APIToolKit\Api\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * @group Gestion des rechargements
 *
 * APIs pour la gestion des rechargements
 */
class RechargeController extends Controller
{
    use ApiResponse;
    
    public function __construct()
    {

    }

    /**
     * Lister tous les rechargements
     *
     * @authenticated
     */
    public function index(): AnonymousResourceCollection
    {
        $recharges = Recharge::with(
            'transaction.entity',
            'transaction.order.entity',
            'paymentMethod',
            'status',
            'createdBy',
            'updatedBy',
            'deletedBy'
        )
            ->accessibleBy(auth()->user())
            ->when(request()->filled('transaction_id'), function ($query) {
                $query->where('transaction_id', Transaction::keyFromHashId(request()->transaction_id));
            })
            ->when(request()->filled('payment_method_id'), function ($query) {
                $query->where('payment_method_id', PaymentMethod::keyFromHashId(request()->payment_method_id));
            })
            ->when(request()->filled('status_code'), function ($query) {
                $query->where('status_id', Status::where('code', request()->status_code)->first()->id);
            })
            ->useFilters()
            ->latest('created_at')
            ->dynamicPaginate();

        return RechargeResource::collection($recharges);
    }

    /**
     * Créer un rechargement
     *
     * @authenticated
     */
    public function store(CreateRechargeRequest $request): JsonResponse
    {
        $transaction = Transaction::accessibleBy(auth()->user())->findOrFail($request->transaction_id);

        $annee = date("Y");
        $mois_jour_heure = date("mdH");
        $time = date("is");
        $today = $annee.'_'.$mois_jour_heure.'_'.$time;
        $reference = 'REF_'.$today;

        $recharge = Recharge::create([
            'reference' => $reference,
            'transaction_id' => $transaction->id,
            'amount' => $transaction->amount,
            'user_first_name' => $request->user_first_name,
            'user_last_name' => $request->user_last_name,
            'user_phone_number' => $request->user_phone_number,
            'whatsapp_phone_number' => $request->whatsapp_phone_number,
            'email' => $request->email,
            'payment_method_id' => $request->payment_method_id,
            'status_id' => Status::where('code', StatusEnum::PENDING)->first()->id,
            'created_by' => auth()?->user()?->id ?? null,
            'updated_by' => auth()?->user()?->id ?? null,
        ]);

        $successUrl = config('services.frontend.url') . '/recharge/success/' . $recharge->reference;
        $errorUrl = config('services.frontend.url') . '/recharge/error/' . $recharge->reference;

        $waveCheckoutService = new WaveCheckoutService();
        $response = $waveCheckoutService->createCheckoutSession($transaction->amount, $recharge->reference, $successUrl, $errorUrl);

        if($response->successful()) {
            $waveCheckoutSession = $waveCheckoutService->searchCheckoutSessions($recharge->reference);
            if($waveCheckoutSession->successful()) {
                $recharge->update([
                    'payment_link' => $waveCheckoutSession['result'][0]['wave_launch_url'],
                ]);
                $transaction->update([
                    'status_id' => Status::where('code', StatusEnum::PERFORMED)->first()->id,
                    'updated_by' => auth()?->user()?->id ?? null,
                ]);

                $transaction->load('calculation');

                if ($recharge->email && $transaction->calculation) {
                    $file = public_path('storage/evaluation_report/'.$transaction->calculation->reference.'.pdf');

                    if (file_exists($file)) {
                        try {
                            $emails = (new EmailValidationService())->validateEmails([$recharge->email]);

                            if (count($emails) > 0) {
                                Mail::to($emails)->send(new SendEvaluationReportMail($file, $transaction->calculation));
                            }
                        } catch (\Exception $e) {
                            Log::error($e);
                        }
                    }
                }
            } else {
                $transaction->update([
                    'status_id' => Status::where('code', StatusEnum::FAILED)->first()->id,
                    'updated_by' => auth()?->user()?->id ?? null,
                ]);
                return $this->responseUnprocessable('Erreur lors de la recherche de la session de paiement.');
            }





            // $recharge->update([
            //     'payment_link' => $response['wave_launch_url'],
            // ]);
            // $transaction->update([
            //     'status_id' => Status::where('code', StatusEnum::PERFORMED)->first()->id,
            //     'updated_by' => auth()?->user()?->id ?? null,
            // ]);

            // $transaction->load('calculation');

            // if ($recharge->email && $transaction->calculation) {
            //     $file = public_path('storage/evaluation_report/'.$transaction->calculation->reference.'.pdf');

            //     if (file_exists($file)) {
            //         try {
            //             $emails = (new EmailValidationService())->validateEmails([$recharge->email]);

            //             if (count($emails) > 0) {
            //                 Mail::to($emails)->send(new SendEvaluationReportMail($file, $transaction->calculation));
            //             }
            //         } catch (\Exception $e) {
            //             Log::error($e);
            //         }
            //     }
            // }

        } else {
            return $this->responseUnprocessable('Erreur lors de la création de la session de paiement.');
        }

        $recharge->load([
            'transaction',
            'transaction.calculation',
            'transaction.entity',
            'transaction.order.entity',
            'paymentMethod',
            'status',
            'createdBy',
            'updatedBy',
        ]);

        $waveCheckoutService = new WaveCheckoutService();
        $waveCheckoutSession = $waveCheckoutService->searchCheckoutSessions($recharge->reference);
        if($waveCheckoutSession->successful()) {
            return $this->responseCreated('Rechargement créé avec succès', new RechargeResource($recharge));
        } else {
            $transaction->update([
                'status_id' => Status::where('code', StatusEnum::FAILED)->first()->id,
                'updated_by' => auth()?->user()?->id ?? null,
            ]);
            return $this->responseUnprocessable('Erreur lors de la recherche de la session de paiement.');
        }

    }

    /**
     * Afficher un rechargement
     *
     * @authenticated
     */
    public function show($id): JsonResponse
    {
        $recharge = Recharge::with(
            'transaction',
            'transaction.calculation',
            'transaction.entity',
            'transaction.order.entity',
            'paymentMethod',
            'status',
            'createdBy',
            'updatedBy',
        )
            ->accessibleBy(auth()->user())
            ->where('recharges.id', Recharge::keyFromHashId($id))
            ->firstOrFail();

        return $this->responseSuccess(null, new RechargeResource($recharge));
    }

    public function getByReference($reference): JsonResponse
    {
        $recharge = Recharge::with(
            'transaction',
            'transaction.calculation',
            'transaction.entity',
            'transaction.order.entity',
            'paymentMethod',
            'status',
            'createdBy',
            'updatedBy',
        )
            ->accessibleBy(auth()->user())
            ->where('recharges.reference', $reference)
            ->firstOrFail();

        return $this->responseSuccess(null, new RechargeResource($recharge));
    }


    /**
     * Mettre à jour le statut des rechargements
     *
     * @authenticated
     */
    public function changeStatus(): JsonResponse
    {
        $recharges = Recharge::accessibleBy(auth()->user())
                        ->where('recharges.status_id', Status::where('code', StatusEnum::PENDING)->first()->id)
                        ->get();
        foreach($recharges as $recharge) {
            $waveCheckoutService = new WaveCheckoutService();
            $waveCheckoutSession = $waveCheckoutService->searchCheckoutSessions($recharge->reference);
            if($waveCheckoutSession->successful()) {
                if($waveCheckoutSession['result'][0]['checkout_status'] == 'completed' && $waveCheckoutSession['result'][0]['payment_status'] == 'succeeded') {
                    $recharge->status_id = Status::where('code', StatusEnum::SUCCESS)->first()->id;
                    $recharge->updated_by = auth()?->user()?->id ?? null;
                    $recharge->save();
                } else {
                    $recharge->status_id = Status::where('code', StatusEnum::FAILED)->first()->id;
                    $recharge->updated_by = auth()?->user()?->id ?? null;
                    $recharge->save();
                }
            }
        }

        return $this->responseSuccess('Statut des rechargements mis à jour avec succès', new RechargeResource($recharges));
    }

}
