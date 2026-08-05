<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionType\UpdateTransactionTypeRequest;
use App\Http\Requests\TransactionType\CreateTransactionTypeRequest;
use App\Http\Resources\TransactionType\TransactionTypeResource;
use App\Models\TransactionType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Essa\APIToolKit\Api\ApiResponse;


/**
 * @group Gestion des types de transaction
 *
 * APIs pour la gestion des types de transaction
 */
class TransactionTypeController extends Controller
{
    use ApiResponse;
    
    public function __construct()
    {

    }

    /**
     * Lister tous les types de transaction
     *
     * @authenticated
     */
    public function index(): AnonymousResourceCollection
    {
        $transactionTypes = TransactionType::useFilters()->latest('created_at')->dynamicPaginate();

        return TransactionTypeResource::collection($transactionTypes);
    }

    /**
     * Créer un type de transaction
     *
     * @authenticated
     */
    public function store(CreateTransactionTypeRequest $request): JsonResponse
    {
        $code = strtolower(str_replace(' ', '', $request->label));
        
        if(TransactionType::where('code', $code)->exists()){
            $code = $code . '-' . now()->timestamp;
        }

        $transactionType = TransactionType::create([
            'code' => $code,
            'label' => $request->label,
            'description' => $request->description,
            'status_id' => Status::where('code', StatusEnum::ACTIVE)->first()->id,
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
        ]);

        return $this->responseCreated('TransactionType created successfully', new TransactionTypeResource($transactionType));
    }

    /**
     * Afficher un type de transaction
     *
     * @authenticated
     */
    public function show($id): JsonResponse
    {
        $transactionType = TransactionType::findOrFail(TransactionType::keyFromHashId($id));

        return $this->responseSuccess(null, new TransactionTypeResource($transactionType));
    }

    /**
     * Mettre à jour un type de transaction
     *
     * @authenticated
     */
    public function update(UpdateTransactionTypeRequest $request, $id): JsonResponse
    {
        $transactionType = TransactionType::findOrFail(TransactionType::keyFromHashId($id));

        $transactionType->update($request->validated());

        return $this->responseSuccess('TransactionType updated Successfully', new TransactionTypeResource($transactionType));
    }

    /**
     * Activer un type de transaction
     *
     * @authenticated
     */
    public function enable($id): JsonResponse
    {
        $transactionType = TransactionType::findOrFail(TransactionType::keyFromHashId($id));
        $transactionType->update([
            'status_id' => Status::where('code', StatusEnum::ACTIVE)->first()->id,
            'updated_by' => auth()->user()->id,
        ]);

        return $this->responseSuccess('TransactionType enabled successfully', new TransactionTypeResource($transactionType));
    }

    /**
     * Désactiver un type de transaction
     *
     * @authenticated
     */
    public function disable($id): JsonResponse
    {
        $transactionType = TransactionType::findOrFail(TransactionType::keyFromHashId($id));
        $transactionType->update([
            'status_id' => Status::where('code', StatusEnum::INACTIVE)->first()->id,
            'updated_by' => auth()->user()->id,
        ]);

        return $this->responseSuccess('TransactionType disabled successfully', new TransactionTypeResource($transactionType));
    }

    /**
     * Supprimer un type de transaction
     *
     * @authenticated
     */
    public function destroy($id): JsonResponse
    {
        $transactionType = TransactionType::findOrFail(TransactionType::keyFromHashId($id));
        $transactionType->update([
            'status_id' => Status::where('code', StatusEnum::DELETED)->first()->id,
            'deleted_at' => now(),
            'deleted_by' => auth()->user()->id,
        ]);
        $transactionType->delete();

        return $this->responseDeleted();
    }

   
}
