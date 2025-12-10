<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GenreResource extends JsonResource
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
            'vehicle_model' => new VehicleModelResource($this->whenLoaded('vehicleModel')),
            'name' => $this->name,
            'slug' => $this->slug,
            'code' => $this->code,
            'max_mileage_essence_per_year' => $this->max_mileage_essence_per_year,
            'max_mileage_diesel_per_year' => $this->max_mileage_diesel_per_year,
            'label' => $this->label,
            'description' => $this->description,
            'disabled_at' => $this->disabled_at?->toISOString(),
            'usages' => UsageResource::collection($this->whenLoaded('usages')),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
