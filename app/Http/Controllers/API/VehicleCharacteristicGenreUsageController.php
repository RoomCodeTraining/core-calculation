<?php

namespace App\Http\Controllers\API;

use App\Enums\StatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\VehicleCharacteristicGenreUsage\CreateVehicleCharacteristicGenreUsageRequest;
use App\Http\Requests\VehicleCharacteristicGenreUsage\UpdateVehicleCharacteristicGenreUsageRequest;
use App\Http\Resources\VehicleCharacteristicGenreUsage\VehicleCharacteristicGenreUsageResource;
use App\Models\Status;
use App\Models\VehicleCharacteristic;
use App\Models\VehicleCharacteristicGenreUsage;
use App\Models\VehicleGenreUsage;
use Essa\APIToolKit\Api\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * @group Gestion des associations caractéristiques / genres-usages
 *
 * APIs pour la gestion des associations entre caractéristiques de véhicules et genres-usages
 */
class VehicleCharacteristicGenreUsageController extends Controller
{
    use ApiResponse;

    public function __construct()
    {
    }

    /**
     * Lister toutes les associations
     *
     * @authenticated
     */
    public function index(): AnonymousResourceCollection
    {
        $vehicleCharacteristicGenreUsages = VehicleCharacteristicGenreUsage::with(
            'vehicleCharacteristic',
            'vehicleGenreUsage.vehicleGenre',
            'vehicleGenreUsage.usage',
            'status',
            'createdBy',
            'updatedBy',
            'deletedBy'
        );

        if (request()->filled('vehicle_characteristic_id')) {
            $vehicleCharacteristicGenreUsages = $vehicleCharacteristicGenreUsages->where(
                'vehicle_characteristic_id',
                VehicleCharacteristic::keyFromHashId(request()->vehicle_characteristic_id)
            );
        }

        if (request()->filled('vehicle_genre_usage_id')) {
            $vehicleCharacteristicGenreUsages = $vehicleCharacteristicGenreUsages->where(
                'vehicle_genre_usage_id',
                VehicleGenreUsage::keyFromHashId(request()->vehicle_genre_usage_id)
            );
        }

        if (request()->filled('status_id')) {
            $vehicleCharacteristicGenreUsages = $vehicleCharacteristicGenreUsages->where(
                'status_id',
                Status::keyFromHashId(request()->status_id)
            );
        }

        $vehicleCharacteristicGenreUsages = $vehicleCharacteristicGenreUsages
            ->useFilters()
            ->latest('id')
            ->dynamicPaginate();

        return VehicleCharacteristicGenreUsageResource::collection($vehicleCharacteristicGenreUsages);
    }

    /**
     * Créer une association
     *
     * @authenticated
     */
    public function store(CreateVehicleCharacteristicGenreUsageRequest $request): JsonResponse
    {
        $vehicleCharacteristicGenreUsage = VehicleCharacteristicGenreUsage::create([
            'vehicle_characteristic_id' => $request->vehicle_characteristic_id,
            'vehicle_genre_usage_id' => $request->vehicle_genre_usage_id,
            'status_id' => Status::where('code', StatusEnum::ACTIVE)->first()->id,
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
        ]);

        $vehicleCharacteristicGenreUsage->load(
            'vehicleCharacteristic',
            'vehicleGenreUsage.vehicleGenre',
            'vehicleGenreUsage.usage',
            'status',
            'createdBy',
            'updatedBy'
        );

        return $this->responseCreated(
            'Association créée avec succès',
            new VehicleCharacteristicGenreUsageResource($vehicleCharacteristicGenreUsage)
        );
    }

    /**
     * Afficher une association
     *
     * @authenticated
     */
    public function show($id): JsonResponse
    {
        $vehicleCharacteristicGenreUsage = VehicleCharacteristicGenreUsage::with(
            'vehicleCharacteristic',
            'vehicleGenreUsage.vehicleGenre',
            'vehicleGenreUsage.usage',
            'status',
            'createdBy',
            'updatedBy',
            'deletedBy'
        )->findOrFail(VehicleCharacteristicGenreUsage::keyFromHashId($id));

        return $this->responseSuccess(null, new VehicleCharacteristicGenreUsageResource($vehicleCharacteristicGenreUsage));
    }

    /**
     * Mettre à jour une association
     *
     * @authenticated
     */
    public function update(UpdateVehicleCharacteristicGenreUsageRequest $request, $id): JsonResponse
    {
        $vehicleCharacteristicGenreUsage = VehicleCharacteristicGenreUsage::findOrFail(
            VehicleCharacteristicGenreUsage::keyFromHashId($id)
        );

        $vehicleCharacteristicGenreUsage->update([
            'vehicle_characteristic_id' => $request->vehicle_characteristic_id,
            'vehicle_genre_usage_id' => $request->vehicle_genre_usage_id,
            'updated_by' => auth()->user()->id,
        ]);

        $vehicleCharacteristicGenreUsage->load(
            'vehicleCharacteristic',
            'vehicleGenreUsage.vehicleGenre',
            'vehicleGenreUsage.usage',
            'status',
            'createdBy',
            'updatedBy'
        );

        return $this->responseSuccess(
            'Association mise à jour avec succès',
            new VehicleCharacteristicGenreUsageResource($vehicleCharacteristicGenreUsage)
        );
    }

    /**
     * Supprimer une association
     *
     * @authenticated
     */
    public function destroy($id): JsonResponse
    {
        $vehicleCharacteristicGenreUsage = VehicleCharacteristicGenreUsage::findOrFail(
            VehicleCharacteristicGenreUsage::keyFromHashId($id)
        );

        $vehicleCharacteristicGenreUsage->update([
            'status_id' => Status::where('code', StatusEnum::DELETED)->first()->id,
            'deleted_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
        ]);
        $vehicleCharacteristicGenreUsage->delete();

        return $this->responseDeleted();
    }
}
