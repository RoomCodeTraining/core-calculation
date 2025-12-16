<?php

namespace App\Http\Controllers\API;

use App\Models\Status;
use App\Enums\StatusEnum;
use App\Models\Calculation;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Essa\APIToolKit\Api\ApiResponse;
use App\Http\Resources\Calculation\CalculationResource;
use App\Http\Requests\Calculation\CreateCalculationRequest;
use App\Http\Requests\Calculation\UpdateCalculationRequest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * @group Gestion des calculs
 *
 * APIs pour la gestion des calculs
 */
class CalculationController extends Controller
{
    use ApiResponse;
    public function __construct()
    {

    }

    /**
     * Lister tous les calculs
     *
     * @authenticated
     */
    public function index(): AnonymousResourceCollection
    {
        $calculations = Calculation::with('entity', 'vehicleCharacteristic', 'status:id,code,label', 'createdBy:id,name', 'updatedBy:id,name', 'deletedBy:id,name')
            ->accessibleBy(auth()->user());

            if(request()->filled('entity_id')){
                $calculations = $calculations->where('entity_id', Entity::keyFromHashId(request()->entity_id));
            }

            if(request()->filled('vehicle_characteristic_id')){
                $calculations = $calculations->where('vehicle_characteristic_id', VehicleCharacteristic::keyFromHashId(request()->vehicle_characteristic_id));
            }

            if(request()->filled('status_id')){
                $calculations = $calculations->where('status_id', Status::keyFromHashId(request()->status_id));
            }   

            $calculations = $calculations->useFilters()
                            ->latest('created_at')
                            ->dynamicPaginate();

        return CalculationResource::collection($calculations);
    }

    /**
     * Créer un calcul
     *
     * @authenticated
     */
    public function store(CreateCalculationRequest $request): JsonResponse
    {
        $calculation = Calculation::create([
            'vehicle_characteristic_id' => $request->vehicle_characteristic_id,
            'entity_id' => auth()->user()->entity_id,
            'status_id' => Status::where('code', StatusEnum::ACTIVE)->first()->id,
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
        ]);

        return $this->responseCreated('Calculation created successfully', new CalculationResource($calculation));
    }

    /**
     * Afficher un calcul
     *
     * @authenticated
     */
    public function show($id): JsonResponse
    {
        $calculation = Calculation::accessibleBy(auth()->user())->findOrFail(Calculation::keyFromHashId($id));
        $calculation->load('entity', 'vehicleCharacteristic', 'status:id,code,label', 'createdBy:id,name', 'updatedBy:id,name', 'deletedBy:id,name');
        return $this->responseSuccess(null, new CalculationResource($calculation));
    }

    /**
     * Mettre à jour un calcul
     *
     * @authenticated
     */
    public function update(UpdateCalculationRequest $request, $id): JsonResponse
    {
        $calculation = Calculation::accessibleBy(auth()->user())->findOrFail(Calculation::keyFromHashId($id));
        $calculation->update([
            'vehicle_characteristic_id' => $request->vehicle_characteristic_id,
            'status_id' => Status::where('code', StatusEnum::ACTIVE)->first()->id,
            'updated_by' => auth()->user()->id,
        ]);

        return $this->responseSuccess('Calculation updated Successfully', new CalculationResource($calculation));
    }

    /**
     * Supprimer un calcul
     *
     * @authenticated
     */
    public function destroy(Calculation $calculation): JsonResponse
    {
        $calculation = Calculation::accessibleBy(auth()->user())->findOrFail(Calculation::keyFromHashId($id));
        $calculation->update([
            'status_id' => Status::where('code', StatusEnum::DELETED)->first()->id,
            'deleted_by' => auth()->user()->id,
            'deleted_at' => Carbon::now(),
        ]);
        $calculation->delete();

        return $this->responseDeleted();
    }

   
}
