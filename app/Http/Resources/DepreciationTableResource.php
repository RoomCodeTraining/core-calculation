<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DepreciationTableResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->hashId,
            'value' => $this->value,
            'genre' => new GenreResource($this->whenLoaded('genre')),
            'vehicle_genre' => new GenreResource($this->whenLoaded('vehicleGenre')), // @deprecated Use genre instead
            'vehicle_age' => new VehicleAgeResource($this->whenLoaded('vehicleAge')),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}

