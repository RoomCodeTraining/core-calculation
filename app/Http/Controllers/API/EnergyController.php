<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\EnergyResource;
use App\Models\Energy;
use Illuminate\Http\JsonResponse;

class EnergyController extends Controller
{
    /**
     * Display a listing of the energies.
     */
    public function index(): JsonResponse
    {
        $energies = Energy::paginate();

        return $this->responseSuccess(
            'Energies retrieved successfully',
            EnergyResource::collection($energies)->response()->getData(true)
        );
    }
}

