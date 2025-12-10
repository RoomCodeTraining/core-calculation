<?php

namespace App\Http\Controllers\API;

use App\Filters\UsageFilters;
use App\Http\Controllers\Controller;
use App\Http\Resources\UsageResource;
use App\Models\Usage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UsageController extends Controller
{
    /**
     * Display a listing of the usages.
     */
    public function index(): JsonResponse
    {
        $usages = Usage::useFilters(UsageFilters::class)
            ->with(['genre.vehicleModel.brand'])
            ->paginate();

        return $this->responseSuccess(
            'Usages retrieved successfully',
            UsageResource::collection($usages)->response()->getData(true)
        );
    }

    /**
     * Display the specified usage.
     */
    public function show(Request $request, string $id): JsonResponse
    {
        $usage = Usage::findByHashId($id);

        if (!$usage) {
            return $this->responseNotFound('Usage not found');
        }

        $usage->load(['genre.vehicleModel.brand']);

        return $this->responseSuccess('Usage retrieved successfully', new UsageResource($usage));
    }
}

