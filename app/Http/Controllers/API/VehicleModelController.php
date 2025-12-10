<?php

namespace App\Http\Controllers\API;

use App\Filters\VehicleModelFilters;
use App\Http\Controllers\Controller;
use App\Http\Resources\VehicleModelResource;
use App\Models\VehicleModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VehicleModelController extends Controller
{
    /**
     * Display a listing of the vehicle models.
     */
    public function index(): JsonResponse
    {
        $vehicleModels = VehicleModel::useFilters(VehicleModelFilters::class)
            ->with(['brand', 'genres'])
            ->paginate();

        return $this->responseSuccess(
            'Vehicle models retrieved successfully',
            VehicleModelResource::collection($vehicleModels)->response()->getData(true)
        );
    }

    /**
     * Display the specified vehicle model.
     */
    public function show(Request $request, string $id): JsonResponse
    {
        $vehicleModel = VehicleModel::findByHashId($id);

        if (!$vehicleModel) {
            return $this->responseNotFound('Vehicle model not found');
        }

        $vehicleModel->load(['brand', 'genres.usages']);

        return $this->responseSuccess('Vehicle model retrieved successfully', new VehicleModelResource($vehicleModel));
    }
}

