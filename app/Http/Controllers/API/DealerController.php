<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dealer\UpdateDealerRequest;
use App\Http\Requests\Dealer\CreateDealerRequest;
use App\Http\Resources\Dealer\DealerResource;
use App\Models\Dealer;
use App\Models\Status;
use App\Enums\StatusEnum;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Essa\APIToolKit\Api\ApiResponse;

/**
 * @group Gestion des concessionnaires
 *
 * APIs pour la gestion des concessionnaires
 */
class DealerController extends Controller
{
    use ApiResponse;
    public function __construct()
    {

    }

    /**
     * Lister tous les concessionnaires
     *
     * @authenticated
     */
    public function index(): AnonymousResourceCollection
    {
        $dealers = Dealer::with('status:id,code,label', 'createdBy:id,name', 'updatedBy:id,name', 'deletedBy:id,name')
            ->latest('created_at')
            ->useFilters()
            ->dynamicPaginate();

        return DealerResource::collection($dealers);
    }

    /**
     * Créer un concessionnaire
     *
     * @authenticated
     */
    public function store(CreateDealerRequest $request): JsonResponse
    {
        $dealer = Dealer::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'status_id' => Status::where('code', StatusEnum::ACTIVE)->first()->id,
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
        ]);

        return $this->responseCreated('Dealer created successfully', new DealerResource($dealer));
    }

    /**
     * Afficher un concessionnaire
     *
     * @authenticated
     */
    public function show($id): JsonResponse
    {
        $dealer = Dealer::findOrFail(Dealer::keyFromHashId($id));
        $dealer->load('status:id,code,label', 'createdBy:id,name', 'updatedBy:id,name', 'deletedBy:id,name');
        return $this->responseSuccess(null, new DealerResource($dealer));
    }

    /**
     * Mettre à jour un concessionnaire
     *
     * @authenticated
     */
    public function update(UpdateDealerRequest $request, $id): JsonResponse
    {
        $dealer = Dealer::findOrFail(Dealer::keyFromHashId($id));
        $dealer->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'status_id' => Status::where('code', StatusEnum::ACTIVE)->first()->id,
            'updated_by' => auth()->user()->id,
        ]);

        return $this->responseSuccess('Dealer updated Successfully', new DealerResource($dealer));
    }

    /**
     * Supprimer un concessionnaire
     *
     * @authenticated
     */
    public function destroy($id): JsonResponse
    {
        $dealer = Dealer::findOrFail(Dealer::keyFromHashId($id));
        $dealer->update([
            'status_id' => Status::where('code', StatusEnum::DELETED)->first()->id,
            'deleted_by' => auth()->user()->id,
            'deleted_at' => Carbon::now(),
        ]);
        $dealer->delete();

        return $this->responseSuccess('Dealer deleted Successfully', null);
    }
   
}
