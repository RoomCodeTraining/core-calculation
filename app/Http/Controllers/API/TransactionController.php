<?php

namespace App\Http\Controllers\API;

use App\Enums\ReceiptTypeEnum;
use App\Enums\StatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\CancelTransactionRequest;
use App\Http\Requests\Transaction\CreateTransactionRequest;
use App\Http\Requests\Transaction\UpdateTransactionRequest;
use App\Http\Resources\Entity\EntityResource;
use App\Http\Resources\Transaction\TransactionResource;
use App\Models\AppSetting;
use App\Models\Calculation;
use App\Models\Entity;
use App\Models\Receipt;
use App\Models\ReceiptType;
use App\Models\Status;
use App\Models\Transaction;
use App\Models\TransactionType;
use Carbon\Carbon;
use Essa\APIToolKit\Api\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * @group Gestion des transactions
 *
 * APIs pour la gestion des transactions
 */
class TransactionController extends Controller
{
    use ApiResponse;
    public function __construct()
    {

    }

    /**
     * Lister toutes les transactions
     *
     * @authenticated
     */
    public function index(): AnonymousResourceCollection
    {
        $transactions = Transaction::with('entity', 'order', 'receipts', 'receipts.receiptType', 'transactionType', 'status', 'createdBy', 'updatedBy', 'deletedBy', 'cancelledBy')->accessibleBy(auth()->user());

        if(request()->filled('entity_id')){
            $transactions = $transactions->where('entity_id', Entity::keyFromHashId(request()->entity_id));
        }

        if(request()->filled('transaction_type_id')){
            $transactions = $transactions->where('transaction_type_id', TransactionType::keyFromHashId(request()->transaction_type_id));
        }
        
        $transactions = $transactions->useFilters()->useFilters()
            ->latest('created_at')
            ->dynamicPaginate();

        return TransactionResource::collection($transactions);
    }

    /**
     * Lister les credits des entités
     *
     * @authenticated
     */
    public function creditsAll(): JsonResponse
    {
        $entities = Transaction::select('entity_id')->distinct()->get();
        $creditsData = [];
        foreach($entities as $entity){
            $credits = Transaction::where('entity_id', $entity->entity_id)->where('status_id', Status::where('code', StatusEnum::PERFORMED)->first()->id)->sum('quantity') - Calculation::where('entity_id', $entity->entity_id)->where('status_id', Status::where('code', StatusEnum::SUCCESS)->first()->id)->count();
            $creditsData[] = [
                'entity' => new EntityResource(Entity::find($entity->entity_id)),
                'credits' => $credits,
            ];
        }
        return $this->responseSuccess('Credits fetched successfully', $creditsData);
    }

    /**
     * Créer une transaction
     *
     * @authenticated
     */
    public function store(CreateTransactionRequest $request): JsonResponse
    {
        $transaction = Transaction::create([
            'reference' => 'TR-'.date('YmdHis'),
            'entity_id' => $request->entity_id,
            'transaction_type_id' => $request->transaction_type_id,
            'quantity' => $request->quantity,
            'description' => $request->description,
            'status_id' => Status::where('code', StatusEnum::PERFORMED)->first()->id,
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
        ]);

        $amount_excluding_tax = AppSetting::where('code', 'credit_cost')->first()->value * ($transaction->quantity ?? 0);
        $amount_tax = $amount_excluding_tax * AppSetting::where('code', 'tax_rate')->first()->value / 100;
        $amount = $amount_excluding_tax + $amount_tax;

        $receipt = Receipt::create([
            'transaction_id' => $transaction->id,
            'receipt_type_id' => ReceiptType::where('code', ReceiptTypeEnum::CREDIT)->first()->id,
            'amount_excluding_tax' => $amount_excluding_tax,
            'amount_tax' => $amount_tax,
            'amount' => $amount,
            'status_id' => Status::where('code', StatusEnum::ACTIVE)->first()->id,
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
        ]);

        return $this->responseCreated('Transaction created successfully', new TransactionResource($transaction));
    }

    /**
     * Afficher une transaction
     *
     * @authenticated
     */
    public function show($id): JsonResponse
    {
        $transaction = Transaction::keyFromHashId($id)->builder()->accessibleBy(auth()->user())->firstOrFail();
        
        $transaction->load('entity', 'order', 'receipts', 'receipts.receiptType', 'transactionType', 'status', 'createdBy', 'updatedBy', 'deletedBy', 'cancelledBy');

        return $this->responseSuccess(null, new TransactionResource($transaction));
    }

    /**
     * Mettre à jour une transaction
     *
     * @authenticated
     */
    public function update(UpdateTransactionRequest $request, $id): JsonResponse
    {
        $transaction = Transaction::keyFromHashId($id)->builder()->accessibleBy(auth()->user())->firstOrFail();
        // $transaction->update([
        //     'entity_id' => Entity::keyFromHashId($request->entity_id),
        //     'transaction_type_id' => TransactionType::keyFromHashId($request->transaction_type_id),
        //     'quantity' => $request->quantity,
        //     'description' => $request->description,
        //     'status_id' => Status::where('code', StatusEnum::PERFORMED)->first()->id,
        //     'updated_by' => auth()->user()->id,
        // ]);

        return $this->responseSuccess('Transaction updated Successfully', new TransactionResource($transaction));
    }

    /**
     * Annuler une transaction
     *
     * @authenticated
     */
    public function cancel(CancelTransactionRequest $request, $id): JsonResponse
    {
        $transaction = Transaction::keyFromHashId($id)->builder()->accessibleBy(auth()->user())->firstOrFail();
        $transaction->update([
            'cancellation_reason' => $request->cancellation_reason,
            'cancelled_by' => auth()->user()->id,
            'cancelled_at' => Carbon::now(),
            'status_id' => Status::where('code', StatusEnum::CANCELLED)->first()->id,
            'updated_by' => auth()->user()->id,
        ]);

        return $this->responseSuccess('Transaction cancelled successfully', new TransactionResource($transaction->load('entity', 'transactionType', 'status', 'createdBy', 'updatedBy', 'deletedBy', 'cancelledBy')));
    }

    /**
     * Supprimer une transaction
     *
     * @authenticated
     */
    public function destroy($id): JsonResponse
    {
        $transaction = Transaction::keyFromHashId($id)->builder()->accessibleBy(auth()->user())->firstOrFail();
        $transaction->update([
            'status_id' => Status::where('code', StatusEnum::DELETED)->first()->id,
            'deleted_by' => auth()->user()->id,
            'deleted_at' => Carbon::now(),
        ]);
        $transaction->delete();

        return $this->responseDeleted();
    }

   
}
