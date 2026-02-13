<?php

namespace App\Http\Controllers\API;

use App\Models\Status;
use App\Enums\StatusEnum;
use App\Models\VehicleGenreUsage;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Essa\APIToolKit\Api\ApiResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Http\Resources\VehicleGenreUsage\VehicleGenreUsageResource;
use App\Http\Requests\VehicleGenreUsage\CreateVehicleGenreUsageRequest;
use App\Http\Requests\VehicleGenreUsage\UpdateVehicleGenreUsageRequest;

/**
 * @group Gestion des usages des genres de véhicules
 *
 * APIs pour la gestion des usages des genres de véhicules
 */
class VehicleGenreUsageController extends Controller
{
    use ApiResponse;
    public function __construct()
    {

    }

    /**
     * Lister tous les usages des genres de véhicules
     *
     * @authenticated
     */
    public function index(): AnonymousResourceCollection
    {
        $vehicleGenreUsages = VehicleGenreUsage::with('vehicleGenre', 'usage', 'status', 'createdBy', 'updatedBy', 'deletedBy');
        
        if(request()->has('vehicle_genre_id')){
            $vehicleGenreUsages = $vehicleGenreUsages->where('vehicle_genre_id', VehicleGenre::keyFromHashId(request()->vehicle_genre_id));
        }

        if(request()->has('usage_id')){
            $vehicleGenreUsages = $vehicleGenreUsages->where('usage_id', Usage::keyFromHashId(request()->usage_id));
        }

        $vehicleGenreUsages = $vehicleGenreUsages->useFilters()
                                ->latest('created_at')
                                ->dynamicPaginate();

        return VehicleGenreUsageResource::collection($vehicleGenreUsages);
    }

    /**
     * Créer un nouvel usage d'un genre de véhicule
     *
     * @authenticated
     */
    public function store(CreateVehicleGenreUsageRequest $request): JsonResponse
    {
        $vehicleGenreUsage = VehicleGenreUsage::create([
            'vehicle_genre_id' => $request->vehicle_genre_id,
            'usage_id' => $request->usage_id,
            'max_mileage_essence_per_year' => $request->max_mileage_essence_per_year,
            'max_mileage_diesel_per_year' => $request->max_mileage_diesel_per_year,
            'status_id' => Status::where('code', StatusEnum::ACTIVE)->first()->id,
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
        ]);

        return $this->responseCreated('VehicleGenreUsage created successfully', new VehicleGenreUsageResource($vehicleGenreUsage));
    }

    /**
     * Afficher un usage d'un genre de véhicule
     *
     * @authenticated
     */
    public function show($id): JsonResponse
    {
        $vehicleGenreUsage = VehicleGenreUsage::findOrFail(VehicleGenreUsage::keyFromHashId($id));
        $vehicleGenreUsage->load('vehicleGenre', 'usage', 'status', 'createdBy', 'updatedBy', 'deletedBy');
        return $this->responseSuccess(null, new VehicleGenreUsageResource($vehicleGenreUsage));
    }

    /**
     * Mettre à jour un usage d'un genre de véhicule
     *
     * @authenticated
     */
    public function update(UpdateVehicleGenreUsageRequest $request, $id): JsonResponse
    {
        $vehicleGenreUsage = VehicleGenreUsage::findOrFail(VehicleGenreUsage::keyFromHashId($id));
        $vehicleGenreUsage->update([
            'vehicle_genre_id' => $request->vehicle_genre_id,
            'usage_id' => $request->usage_id,
            'max_mileage_essence_per_year' => $request->max_mileage_essence_per_year,
            'max_mileage_diesel_per_year' => $request->max_mileage_diesel_per_year,
            'updated_by' => auth()->user()->id,
        ]);

        return $this->responseSuccess('VehicleGenreUsage updated Successfully', new VehicleGenreUsageResource($vehicleGenreUsage));
    }

    /**
     * Supprimer un usage d'un genre de véhicule
     *
     * @authenticated
     */
    public function destroy($id): JsonResponse
    {
        $vehicleGenreUsage = VehicleGenreUsage::findOrFail(VehicleGenreUsage::keyFromHashId($id));
        $vehicleGenreUsage->update([
            'status_id' => Status::where('code', StatusEnum::DELETED)->first()->id,
            'deleted_by' => auth()->user()->id,
            'deleted_at' => Carbon::now(),
        ]);
        $vehicleGenreUsage->delete();

        return $this->responseDeleted();
    }

   
}
