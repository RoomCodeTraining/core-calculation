<?php

namespace App\Http\Controllers\API;

use App\Filters\GenreFilters;
use App\Http\Controllers\Controller;
use App\Http\Resources\GenreResource;
use App\Models\Genre;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    /**
     * Display a listing of the genres.
     */
    public function index(): JsonResponse
    {
        $genres = Genre::useFilters(GenreFilters::class)
            ->with(['vehicleModel.brand', 'usages'])
            ->paginate();

        return $this->responseSuccess(
            'Genres retrieved successfully',
            GenreResource::collection($genres)->response()->getData(true)
        );
    }

    /**
     * Display the specified genre.
     */
    public function show(Request $request, string $id): JsonResponse
    {
        $genre = Genre::findByHashId($id);

        if (!$genre) {
            return $this->responseNotFound('Genre not found');
        }

        $genre->load(['vehicleModel.brand', 'usages']);

        return $this->responseSuccess('Genre retrieved successfully', new GenreResource($genre));
    }
}

