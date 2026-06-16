<?php

namespace App\Http\Controllers\API;

use App\Models\Price;
use App\Models\Usage;
use App\Models\Dealer;
use App\Models\Status;
use App\Enums\StatusEnum;
use App\Models\VehicleGenre;
use App\Models\VehicleModel;
use App\Models\VehicleEnergy;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Essa\APIToolKit\Api\ApiResponse;
use App\Models\VehicleCharacteristic;
use App\Models\VehicleCharacteristicGenreUsage;
use App\Http\Resources\Usage\UsageResource;
use App\Http\Resources\VehicleGenre\VehicleGenreResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Http\Resources\VehicleGenreUsage\VehicleGenreUsageResource;
use App\Http\Resources\VehicleCharacteristic\VehicleCharacteristicResource;
use App\Http\Requests\VehicleCharacteristic\CreateVehicleCharacteristicRequest;
use App\Http\Requests\VehicleCharacteristic\UpdateVehicleCharacteristicRequest;

/**
 * @group Gestion des caractéristiques des véhicules
 *
 * APIs pour la gestion des caractéristiques des véhicules
 */
class VehicleCharacteristicController extends Controller
{
    use ApiResponse;
    public function __construct()
    {

    }

    /**
     * Lister toutes les caractéristiques des véhicules
     *
     * @authenticated
     */
    public function index(): AnonymousResourceCollection
    {
        $vehicleCharacteristics = VehicleCharacteristic::with(
            'vehicleModel',
            'vehicleGenreUsages',
            'vehicleGenreUsages.vehicleGenre',
            'vehicleGenreUsages.usage',
            'vehicleEnergy',
            'dealer',
            'status:id,code,label',
            'createdBy:id,name',
            'updatedBy:id,name',
            'deletedBy:id,name'
        );

        $vehicleCharacteristics = $this->applyAllFilters($vehicleCharacteristics);

        $vehicleCharacteristics = $vehicleCharacteristics
            ->useFilters()
            ->latest('created_at')
            ->dynamicPaginate();

        return VehicleCharacteristicResource::collection($vehicleCharacteristics);
    }

    /**
     * Créer une caractéristique de véhicule
     *
     * @authenticated
     */
    public function store(CreateVehicleCharacteristicRequest $request): JsonResponse
    {
        $vehicleCharacteristic = VehicleCharacteristic::create([
            'vehicle_model_id' => $request->vehicle_model_id,
            'vehicle_energy_id' => $request->vehicle_energy_id,
            'dealer_id' => $request->dealer_id,
            'type' => $request->type,
            'equipments' => $request->equipments,
            'options' => $request->options,
            'fiscal_power' => $request->fiscal_power,
            'nb_seats' => $request->nb_seats,
            'status_id' => Status::where('code', StatusEnum::ACTIVE)->first()->id,
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
        ]);

        $vehicleCharacteristic->vehicleGenreUsages()->sync(
            VehicleCharacteristicGenreUsage::mapSyncData($request->vehicle_genre_usage_ids)
        );

        if($vehicleCharacteristic && $request->new_market_value){
            Price::create([
                'value' => $request->new_market_value,
                'date' => $request->date,
                'vehicle_characteristic_id' => $vehicleCharacteristic->id,
                'status_id' => Status::where('code', StatusEnum::ACTIVE)->first()->id,
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id,
            ]);
        }

        $vehicleCharacteristic->load(
            'vehicleModel',
            'vehicleGenreUsages.vehicleGenre',
            'vehicleGenreUsages.usage',
            'vehicleEnergy',
            'dealer',
            'status',
            'createdBy',
            'updatedBy'
        );

        return $this->responseCreated('VehicleCharacteristic created successfully', new VehicleCharacteristicResource($vehicleCharacteristic));
    }

    /**
     * Afficher une caractéristique de véhicule
     *
     * @authenticated
     */
    public function show($id): JsonResponse
    {
        $vehicleCharacteristic = VehicleCharacteristic::findOrFail(VehicleCharacteristic::keyFromHashId($id));
        $vehicleCharacteristic->load(
            'vehicleModel:id,label',
            'vehicleGenreUsages',
            'vehicleGenreUsages.vehicleGenre',
            'vehicleGenreUsages.usage',
            'vehicleEnergy:id,label',
            'dealer:id,name',
            'status:id,code,label',
            'createdBy:id,name',
            'updatedBy:id,name',
            'deletedBy:id,name'
        );

        return $this->responseSuccess(null, new VehicleCharacteristicResource($vehicleCharacteristic));
    }

    /**
     * Mettre à jour une caractéristique de véhicule
     *
     * @authenticated
     */
    public function update(UpdateVehicleCharacteristicRequest $request, $id): JsonResponse
    {
        $vehicleCharacteristic = VehicleCharacteristic::findOrFail(VehicleCharacteristic::keyFromHashId($id));
        $vehicleCharacteristic->update([
            'vehicle_model_id' => $request->vehicle_model_id,
            'vehicle_energy_id' => $request->vehicle_energy_id,
            'dealer_id' => $request->dealer_id,
            'type' => $request->type,
            'equipments' => $request->equipments,
            'options' => $request->options,
            'fiscal_power' => $request->fiscal_power,
            'nb_seats' => $request->nb_seats,
            'new_market_value' => $request->new_market_value,
            'status_id' => Status::where('code', StatusEnum::ACTIVE)->first()->id,
            'updated_by' => auth()->user()->id,
        ]);

        $vehicleCharacteristic->vehicleGenreUsages()->sync(
            VehicleCharacteristicGenreUsage::mapSyncData($request->vehicle_genre_usage_ids)
        );
        $vehicleCharacteristic->load(
            'vehicleModel',
            'vehicleGenreUsages.vehicleGenre',
            'vehicleGenreUsages.usage',
            'vehicleEnergy',
            'dealer',
            'status',
            'createdBy',
            'updatedBy'
        );

        return $this->responseSuccess('VehicleCharacteristic updated Successfully', new VehicleCharacteristicResource($vehicleCharacteristic));
    }

    /**
     * Supprimer une caractéristique de véhicule
     *
     * @authenticated
     */
    public function destroy($id): JsonResponse
    {
        $vehicleCharacteristic = VehicleCharacteristic::findOrFail(VehicleCharacteristic::keyFromHashId($id));
        $vehicleCharacteristic->delete();

        return $this->responseSuccess('VehicleCharacteristic deleted Successfully', null);
    }

    /**
     * Filtrer toutes les caractéristiques des véhicules
     *
     * @authenticated
     */
    public function filterAll(): AnonymousResourceCollection
    {
        $vehicleCharacteristics = VehicleCharacteristic::with(
            'vehicleModel',
            'vehicleGenreUsages',
            'vehicleGenreUsages.vehicleGenre',
            'vehicleGenreUsages.usage',
            'vehicleEnergy',
            'dealer',
            'status:id,code,label',
            'createdBy:id,name',
            'updatedBy:id,name',
            'deletedBy:id,name'
        );

        $vehicleCharacteristics = $this->applyAllFilters($vehicleCharacteristics);

        $vehicleCharacteristics = $vehicleCharacteristics
            ->useFilters()
            ->latest('created_at')
            ->dynamicPaginate();

        // Récupérer les usages des genres de véhicules
        $vehicleGenreUsages = $vehicleCharacteristics
            ->flatMap(fn ($vehicleCharacteristic) => $vehicleCharacteristic->vehicleGenreUsages)
            ->unique('id')
            ->values();

        $vehicleGenres = $vehicleGenreUsages
            ->map(fn ($vehicleGenreUsage) => $vehicleGenreUsage->vehicleGenre)
            ->filter()
            ->unique('id')
            ->values();

        $usages = $vehicleGenreUsages
            ->map(fn ($vehicleGenreUsage) => $vehicleGenreUsage->usage)
            ->filter()
            ->unique('id')
            ->values();

        if (request()->filled('usage_id')) {
            return VehicleCharacteristicResource::collection($vehicleCharacteristics)->additional([
                'vehicle_genre_usages' => null,
                'vehicle_genres' => null,
                'usages' => null,
            ]);
        }

        return VehicleCharacteristicResource::collection($vehicleCharacteristics)->additional([
            'vehicle_genre_usages' => VehicleGenreUsageResource::collection($vehicleGenreUsages),
            'vehicle_genres' => VehicleGenreResource::collection($vehicleGenres),
            'usages' => UsageResource::collection($usages),
        ]);
    }

    private function applyAllFilters($query)
    {
        if (request()->filled('dealer_id')) {
            $query = $query->where(
                'dealer_id',
                Dealer::keyFromHashId(request()->dealer_id)
            );
        }

        if (request()->filled('vehicle_energy_id')) {
            $query = $query->where(
                'vehicle_energy_id',
                VehicleEnergy::keyFromHashId(request()->vehicle_energy_id)
            );
        }

        if (request()->filled('status_id')) {
            $query = $query->where(
                'status_id',
                Status::keyFromHashId(request()->status_id)
            );
        }

        if (request()->filled('vehicle_model_id')) {
            $query = $query->where(
                'vehicle_model_id',
                VehicleModel::keyFromHashId(request()->vehicle_model_id)
            );
        }

        $vehicleGenreId = request()->filled('vehicle_genre_id')
            ? VehicleGenre::keyFromHashId(request()->vehicle_genre_id)
            : null;
        $usageId = request()->filled('usage_id')
            ? Usage::keyFromHashId(request()->usage_id)
            : null;

        if ($vehicleGenreId !== null || $usageId !== null) {
            $query = $query->whereHas('vehicleCharacteristicGenreUsages.vehicleGenreUsage', function ($subQuery) use ($vehicleGenreId, $usageId) {
                if ($vehicleGenreId !== null) {
                    $subQuery->where('vehicle_genre_usages.vehicle_genre_id', $vehicleGenreId);
                }

                if ($usageId !== null) {
                    $subQuery->where('vehicle_genre_usages.usage_id', $usageId);
                }
            });

            // Garder uniquement les associations genre/usage qui matchent le filtre
            // (sinon la ressource renvoie toutes les associations de la caractéristique).
            $query = $query->with([
                'vehicleGenreUsages' => function ($vehicleGenreUsageQuery) use ($vehicleGenreId, $usageId) {
                    if ($vehicleGenreId !== null) {
                        $vehicleGenreUsageQuery->where('vehicle_genre_id', $vehicleGenreId);
                    }

                    if ($usageId !== null) {
                        $vehicleGenreUsageQuery->where('usage_id', $usageId);
                    }
                },
            ]);
        }

        return $query;
    }
}
