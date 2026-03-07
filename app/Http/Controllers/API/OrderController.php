<?php

namespace App\Http\Controllers\API;

use App\Enums\ReceiptTypeEnum;
use App\Enums\StatusEnum;
use App\Enums\TransactionTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\CancelOrderRequest;
use App\Http\Requests\Order\CreateOrderRequest;
use App\Http\Requests\Order\RejectOrderRequest;
use App\Http\Requests\Order\UpdateOrderRequest;
use App\Http\Resources\Order\OrderResource;
use App\Models\AppSetting;
use App\Models\Entity;
use App\Models\Order;
use App\Models\Receipt;
use App\Models\ReceiptType;
use App\Models\Status;
use App\Models\Transaction;
use App\Models\TransactionType;
use App\Models\User;
use Carbon\Carbon;
use Essa\APIToolKit\Api\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * @group Gestion des commandes de crédit
 *
 * APIs pour la gestion des commandes de crédit
 */
class OrderController extends Controller
{
    use ApiResponse;
    
    public function __construct()
    {

    }

    /**
     * Lister les commandes de crédit
     *
     * @authenticated
     */
    public function index(): AnonymousResourceCollection
    {
        $orders = Order::with(['entity', 'transactions', 'transactions.receipts', 'transactions.receipts.receiptType', 'validatedBy', 'cancelledBy', 'rejectedBy', 'status', 'createdBy', 'updatedBy', 'deletedBy'])
            ->accessibleBy(auth()->user())
            ->when(request()->filled('entity_id'), function ($query) {
                $query->where('entity_id', Entity::keyFromHashId(request()->entity_id));
            })
            ->when(request()->filled('validated_by'), function ($query) {
                $query->where('validated_by', User::keyFromHashId(request()->validated_by));
            })
            ->when(request()->filled('cancelled_by'), function ($query) {
                $query->where('cancelled_by', User::keyFromHashId(request()->cancelled_by));
            })
            ->when(request()->filled('rejected_by'), function ($query) {
                $query->where('rejected_by', User::keyFromHashId(request()->rejected_by));
            })
            ->when(request()->filled('status_id'), function ($query) {
                $query->where('status_id', Status::keyFromHashId(request()->status_id));
            })
            ->when(request()->filled('created_by'), function ($query) {
                $query->where('created_by', User::keyFromHashId(request()->created_by));
            })
            ->when(request()->filled('updated_by'), function ($query) {
                $query->where('updated_by', User::keyFromHashId(request()->updated_by));
            })
            ->when(request()->filled('deleted_by'), function ($query) {
                $query->where('deleted_by', User::keyFromHashId(request()->deleted_by));
            })
            ->useFilters()
            ->latest('created_at')
            ->dynamicPaginate();

        return OrderResource::collection($orders);
    }

    /**
     * Créer une commande de crédit
     *
     * @authenticated
     */
    public function store(CreateOrderRequest $request): JsonResponse
    {
        $now = Carbon::now();
        $annee = date("Y");
        $mois_jour_heure = date("mdH");
        $time = date("is");
        $today = $annee.'_'.$mois_jour_heure.'_'.$time;
        $reference = 'ORD'.$today;

        $order = Order::create([
            'reference' => $reference,
            'quantity' => $request->quantity,
            'entity_id' => auth()->user()->entity_id,
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
            'status_id' => Status::where('code', StatusEnum::PENDING)->first()->id,
        ]);

        return $this->responseCreated('Order created successfully', new OrderResource($order->load('entity', 'status', 'createdBy')));
    }

    /**
     * Afficher une commande de crédit
     *
     * @authenticated
     */
    public function show($id): JsonResponse
    {
        $order = Order::with('entity', 'transactions', 'transactions.receipts', 'transactions.receipts.receiptType', 'validatedBy', 'cancelledBy', 'rejectedBy', 'status', 'createdBy', 'updatedBy', 'deletedBy')->accessibleBy(auth()->user())->findOrFail(Order::keyFromHashId($id));
        return $this->responseSuccess(null, new OrderResource($order));
    }

    /**
     * Mettre à jour une commande de crédit
     *
     * @authenticated
     */
    public function update(UpdateOrderRequest $request, $id): JsonResponse
    {
        $order = Order::accessibleBy(auth()->user())->findOrFail(Order::keyFromHashId($id));

        if($order->status_id == Status::where('code', StatusEnum::PENDING)->first()->id){
            $order->update([
                'quantity' => $request->quantity,
                'status_id' => Status::where('code', StatusEnum::PENDING)->first()->id,
                'updated_by' => auth()->user()->id,
            ]);
            return $this->responseSuccess('Order updated Successfully', new OrderResource($order->load('entity', 'status', 'createdBy', 'updatedBy')));
        }

        return $this->responseUnprocessable("Impossible de mettre à jour la commande de crédit", null);
    }

    /**
     * Valider une commande de crédit
     *
     * @authenticated
     */
    public function validate($id): JsonResponse
    {
        $order = Order::accessibleBy(auth()->user())->findOrFail(Order::keyFromHashId($id));

        if($order->status_id == Status::where('code', StatusEnum::PENDING)->first()->id){
            $order->update([
                'status_id' => Status::where('code', StatusEnum::VALIDATED)->first()->id,
                'validated_by' => auth()->user()->id,
                'validated_at' => Carbon::now(),
                'updated_by' => auth()->user()->id,
            ]);

            $transaction = Transaction::create([
                'reference' => 'TR-'.date('YmdHis'),
                'entity_id' => $order->entity_id,
                'order_id' => $order->id,
                'transaction_type_id' => TransactionType::where('code', TransactionTypeEnum::DEPOSIT)->first()->id,
                'quantity' => $order->quantity,
                'description' => 'Commande de crédit validée',
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

            return $this->responseSuccess('Order validated Successfully', new OrderResource($order->load('entity', 'status', 'validatedBy', 'createdBy', 'updatedBy')));
        } 

        return $this->responseUnprocessable("Impossible de valider la commande de crédit", null);
    }

    /**
     * Annuler une commande de crédit
     *
     * @authenticated
     */
    public function cancel(CancelOrderRequest $request, $id): JsonResponse
    {
        $order = Order::accessibleBy(auth()->user())->findOrFail(Order::keyFromHashId($id));

        if($order->status_id == Status::where('code', StatusEnum::PENDING)->first()->id){
            $order->update([
                'status_id' => Status::where('code', StatusEnum::CANCELLED)->first()->id,
                'cancelled_by' => auth()->user()->id,
                'cancelled_at' => Carbon::now(),
                'updated_by' => auth()->user()->id,
            ]);
            return $this->responseSuccess('Order canceled Successfully', new OrderResource($order->load('entity', 'status', 'cancelledBy', 'createdBy', 'updatedBy')));
        }
    }

    /**
     * Rejeter une commande de crédit
     *
     * @authenticated
     */
    public function reject(RejectOrderRequest $request, $id): JsonResponse
    {
        $order = Order::accessibleBy(auth()->user())->findOrFail(Order::keyFromHashId($id));

        if($order->status_id == Status::where('code', StatusEnum::PENDING)->first()->id){
            $order->update([
                'status_id' => Status::where('code', StatusEnum::REJECTED)->first()->id,
                'rejected_by' => auth()->user()->id,
                'rejected_at' => Carbon::now(),
                'updated_by' => auth()->user()->id,
            ]);
            return $this->responseSuccess('Order rejected Successfully', new OrderResource($order->load('entity', 'status', 'rejectedBy', 'createdBy', 'updatedBy')));
        }
        return $this->responseUnprocessable("Impossible de rejeter la commande de crédit", null);
    }

   
}