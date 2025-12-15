<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Price\UpdatePriceRequest;
use App\Http\Requests\Price\CreatePriceRequest;
use App\Http\Resources\Price\PriceResource;
use App\Models\Price;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Essa\APIToolKit\Api\ApiResponse;
use App\Enums\StatusEnum;
use App\Models\Status;
use Carbon\Carbon;

/**
 * @group Gestion des prix des caractéristiques des véhicules
 *
 * APIs pour la gestion des prix des caractéristiques des véhicules
 */
class PriceController extends Controller
{
    use ApiResponse;
    public function __construct()
    {

    }

    /**
     * Lister tous les prix des caractéristiques des véhicules
     *
     * @authenticated
     */
    public function index(): AnonymousResourceCollection
    {
        $prices = Price::with('vehicleCharacteristic', 'status', 'createdBy', 'updatedBy', 'deletedBy');

        if(request()->has('vehicle_characteristic_id')){
            $prices->where('vehicle_characteristic_id', request()->vehicle_characteristic_id);
        }

        if(request()->has('status_id')){
            $prices->where('status_id', request()->status_id);
        }
        
        $prices = $prices->useFilters()->latest('date')->dynamicPaginate();

        return PriceResource::collection($prices);
    }

    /**
     * Ajouter un prix d'une caractéristique d'un véhicule
     *
     * @authenticated
     */
    public function store(CreatePriceRequest $request): JsonResponse
    {
        $price = Price::create([
            'value' => $request->value,
            'date' => $request->date,
            'vehicle_characteristic_id' => $request->vehicle_characteristic_id,
            'status_id' => Status::where('code', StatusEnum::ACTIVE)->first()->id,
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
        ]);

        return $this->responseCreated('Price created successfully', new PriceResource($price));
    }

    /**
     * Afficher un prix d'une caractéristique d'un véhicule
     *
     * @authenticated
     */
    public function show($id): JsonResponse
    {
        $price = Price::findOrFail(Price::keyFromHashId($id));

        return $this->responseSuccess(null, new PriceResource($price->load('vehicleCharacteristic', 'status', 'createdBy', 'updatedBy', 'deletedBy')));
    }

    /**
     * Mettre à jour un prix d'une caractéristique d'un véhicule
     *
     * @authenticated
     */
    public function update(UpdatePriceRequest $request, $id): JsonResponse
    {
        $price = Price::findOrFail(Price::keyFromHashId($id));
        $price->update([
            'value' => $request->value,
            'date' => $request->date,
            'vehicle_characteristic_id' => $request->vehicle_characteristic_id,
            'status_id' => Status::where('code', StatusEnum::ACTIVE)->first()->id,
            'updated_by' => auth()->user()->id,
        ]);

        return $this->responseSuccess('Price updated Successfully', new PriceResource($price));
    }

    /**
     * Supprimer un prix d'une caractéristique d'un véhicule
     *
     * @authenticated
     */
    public function destroy($id): JsonResponse
    {
        $price = Price::findOrFail(Price::keyFromHashId($id));
        $price->update([
            'status_id' => Status::where('code', StatusEnum::DELETED)->first()->id,
            'deleted_by' => auth()->user()->id,
            'deleted_at' => Carbon::now(),
        ]);
        $price->delete();

        return $this->responseDeleted();
    }

   
}
