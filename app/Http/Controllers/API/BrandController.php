<?php

namespace App\Http\Controllers\API;

use App\Filters\BrandFilters;
use App\Http\Controllers\Controller;
use App\Http\Resources\BrandResource;
use App\Models\Brand;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    /**
     * Display a listing of the brands.
     */
    public function index(): JsonResponse
    {
        $brands = Brand::useFilters(BrandFilters::class)
            ->with('vehicleModels')
            ->paginate();

        return $this->responseSuccess(
            'Brands retrieved successfully',
            BrandResource::collection($brands)->response()->getData(true)
        );
    }

    /**
     * Display the specified brand.
     */
    public function show(Request $request, string $id): JsonResponse
    {
        $brand = Brand::findByHashId($id);

        if (!$brand) {
            return $this->responseNotFound('Brand not found');
        }

        $brand->load('vehicleModels.genres.usages');

        return $this->responseSuccess('Brand retrieved successfully', new BrandResource($brand));
    }
}

