<?php

namespace App\Http\Controllers\API;

use App\Models\Usage;
use App\Models\Dealer;
use App\Models\Status;
use App\Enums\StatusEnum;
use App\Models\VehicleGenre;
use App\Models\VehicleModel;
use App\Models\VehicleEnergy;
use App\Models\VehicleGenreUsage;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Essa\APIToolKit\Api\ApiResponse;
use App\Models\VehicleCharacteristic;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
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
            'vehicleGenreUsage',
            'vehicleGenreUsage.vehicleGenre',
            'vehicleGenreUsage.usage',
            'vehicleEnergy',
            'dealer',
            'status:id,code,label',
            'createdBy:id,name',
            'updatedBy:id,name',
            'deletedBy:id,name'
        );

        if (request()->filled('dealer_id')) {
            $vehicleCharacteristics = $vehicleCharacteristics->where(
                'dealer_id',
                Dealer::keyFromHashId(request()->dealer_id)
            );
        }

        if (request()->filled('vehicle_energy_id')) {
            $vehicleCharacteristics = $vehicleCharacteristics->where(
                'vehicle_energy_id',
                VehicleEnergy::keyFromHashId(request()->vehicle_energy_id)
            );
        }

        if (request()->filled('status_id')) {
            $vehicleCharacteristics = $vehicleCharacteristics->where(
                'status_id',
                Status::keyFromHashId(request()->status_id)
            );
        }

        if (request()->filled('vehicle_model_id')) {
            $vehicleCharacteristics = $vehicleCharacteristics->where(
                'vehicle_model_id',
                VehicleModel::keyFromHashId(request()->vehicle_model_id)
            );
        }

        if (request()->filled('vehicle_genre_id')) {
            $vehicleCharacteristics = $vehicleCharacteristics->whereHas('vehicleGenreUsage', function ($query) {
                $query->where(
                    'vehicle_genre_id',
                    VehicleGenre::keyFromHashId(request()->vehicle_genre_id)
                );
            });
        }

        if (request()->filled('usage_id')) {
            $vehicleCharacteristics = $vehicleCharacteristics->whereHas('vehicleGenreUsage', function ($query) {
                $query->where(
                    'usage_id',
                    Usage::keyFromHashId(request()->usage_id)
                );
            });
        }

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
            'vehicle_genre_usage_id' => $request->vehicle_genre_usage_id,
            'vehicle_energy_id' => $request->vehicle_energy_id,
            'dealer_id' => $request->dealer_id,
            'type' => $request->type,
            'options' => $request->options,
            'fiscal_power' => $request->fiscal_power,
            'nb_seats' => $request->nb_seats,
            'status_id' => Status::where('code', StatusEnum::ACTIVE)->first()->id,
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
        ]);

        if($vehicleCharacteristic && $request->price){
            Price::create([
                'value' => $request->price,
                'date' => $request->date,
                'vehicle_characteristic_id' => $vehicleCharacteristic->id,
                'status_id' => Status::where('code', StatusEnum::ACTIVE)->first()->id,
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id,
            ]);
        }

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
        $vehicleCharacteristic->load('vehicleModel:id,label', 'vehicleGenreUsage', 'vehicleGenreUsage.vehicleGenre', 'vehicleGenreUsage.usage', 'vehicleEnergy:id,label', 'dealer:id,name', 'status:id,code,label', 'createdBy:id,name', 'updatedBy:id,name', 'deletedBy:id,name');

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
            'vehicle_genre_usage_id' => $request->vehicle_genre_usage_id,
            'vehicle_energy_id' => $request->vehicle_energy_id,
            'dealer_id' => $request->dealer_id,
            'type' => $request->type,
            'options' => $request->options,
            'fiscal_power' => $request->fiscal_power,
            'nb_seats' => $request->nb_seats,
            'new_market_value' => $request->new_market_value,
            'status_id' => Status::where('code', StatusEnum::ACTIVE)->first()->id,
            'updated_by' => auth()->user()->id,
        ]);

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
     * Filtrer toutes les caractéristiques des véhicules par le modèle de véhicule
     *
     * @authenticated
     */
    public function filterByVehicleModel(): AnonymousResourceCollection
    {
        $vehicleCharacteristics = VehicleCharacteristic::with(
            'vehicleModel',
            'vehicleGenreUsage',
            'vehicleGenreUsage.vehicleGenre',
            'vehicleGenreUsage.usage',
            'vehicleEnergy',
            'dealer',
            'status:id,code,label',
            'createdBy:id,name',
            'updatedBy:id,name',
            'deletedBy:id,name'
        );

        if (request()->filled('dealer_id')) {
            $vehicleCharacteristics = $vehicleCharacteristics->where(
                'dealer_id',
                Dealer::keyFromHashId(request()->dealer_id)
            );
        }

        if (request()->filled('vehicle_energy_id')) {
            $vehicleCharacteristics = $vehicleCharacteristics->where(
                'vehicle_energy_id',
                VehicleEnergy::keyFromHashId(request()->vehicle_energy_id)
            );
        }

        if (request()->filled('status_id')) {
            $vehicleCharacteristics = $vehicleCharacteristics->where(
                'status_id',
                Status::keyFromHashId(request()->status_id)
            );
        }

        if (request()->filled('vehicle_model_id')) {
            $vehicleCharacteristics = $vehicleCharacteristics->where(
                'vehicle_model_id',
                VehicleModel::keyFromHashId(request()->vehicle_model_id)
            );
        }

        if (request()->filled('vehicle_genre_id')) {
            $vehicleCharacteristics = $vehicleCharacteristics->whereHas('vehicleGenreUsage', function ($query) {
                $query->where(
                    'vehicle_genre_id',
                    VehicleGenre::keyFromHashId(request()->vehicle_genre_id)
                );
            });
        }

        if (request()->filled('usage_id')) {
            $vehicleCharacteristics = $vehicleCharacteristics->whereHas('vehicleGenreUsage', function ($query) {
                $query->where(
                    'usage_id',
                    Usage::keyFromHashId(request()->usage_id)
                );
            });
        }

        $vehicleCharacteristics = $vehicleCharacteristics
            ->useFilters()
            ->latest('created_at')
            ->dynamicPaginate();

        return VehicleCharacteristicResource::collection($vehicleCharacteristics);
    }
}
