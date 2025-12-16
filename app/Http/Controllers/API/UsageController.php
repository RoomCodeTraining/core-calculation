<?php

namespace App\Http\Controllers\API;

use App\Models\Usage;
use App\Models\Status;
use App\Enums\StatusEnum;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Essa\APIToolKit\Api\ApiResponse;
use App\Http\Resources\Usage\UsageResource;
use App\Http\Requests\Usage\CreateUsageRequest;
use App\Http\Requests\Usage\UpdateUsageRequest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * @group Gestion des usages de véhicules
 *
 * APIs pour la gestion des usages
 */
class UsageController extends Controller
{
    use ApiResponse;
    
    public function __construct()
    {

    }

    /**
     * Lister tous les usages de véhicules
     *
     * @authenticated
     */
    public function index(): AnonymousResourceCollection
    {
        $usages = Usage::with('status:id,code,label', 'createdBy:id,name', 'updatedBy:id,name', 'deletedBy:id,name');

        $usages = $usages->useFilters()
                    ->latest('created_at')
                    ->dynamicPaginate();   

        return UsageResource::collection($usages);
    }

    /**
     * Ajouter un usage de véhicule
     *
     * @authenticated
     */
    public function store(CreateUsageRequest $request): JsonResponse
    {
        $code = strtolower(str_replace(' ', '', $request->label));
        $usage = Usage::create([
            'code' => $code,
            'label' => $request->label,
            'description' => $request->description,
            'status_id' => Status::where('code', StatusEnum::ACTIVE)->first()->id,
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
        ]);

        return $this->responseCreated('Usage created successfully', new UsageResource($usage));
    }

    /**
     * Afficher un usage de véhicule
     *
     * @authenticated
     */
    public function show($id): JsonResponse
    {
        $usage = Usage::findOrFail(Usage::keyFromHashId($id));
        $usage->load('status:id,code,label', 'createdBy:id,name', 'updatedBy:id,name', 'deletedBy:id,name');
        return $this->responseSuccess(null, new UsageResource($usage));
    }

    /**
     * Mettre à jour un usage de véhicule
     *
     * @authenticated
     */
    public function update(UpdateUsageRequest $request, $id): JsonResponse
    {
        $usage = Usage::findOrFail(Usage::keyFromHashId($id));
        $usage->update([
            'label' => $request->label,
            'description' => $request->description,
            'updated_by' => auth()->user()->id,
        ]);

        return $this->responseSuccess('Usage updated Successfully', new UsageResource($usage));
    }

    /**
     * Supprimer un usage de véhicule
     *
     * @authenticated
     */
    public function destroy($id): JsonResponse
    {
        $usage = Usage::findOrFail(Usage::keyFromHashId($id));
        $usage->update([
            'status_id' => Status::where('code', StatusEnum::DELETED)->first()->id,
            'deleted_by' => auth()->user()->id,
            'deleted_at' => Carbon::now(),
        ]);

        $usage->delete();

        return $this->responseSuccess('Usage deleted Successfully', null);
    }
}
