<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UsageResource extends JsonResource
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
            'name' => $this->name,
            'slug' => $this->slug,
            'code' => $this->code,
            'label' => $this->label,
            'description' => $this->description,
            'max_mileage_essence_per_year' => $this->max_mileage_essence_per_year,
            'max_mileage_diesel_per_year' => $this->max_mileage_diesel_per_year,
            'genre' => new GenreResource($this->whenLoaded('genre')),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
