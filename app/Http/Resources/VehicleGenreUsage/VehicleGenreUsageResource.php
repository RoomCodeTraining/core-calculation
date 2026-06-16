<?php

namespace App\Http\Resources\VehicleGenreUsage;

use App\Http\Resources\Status\StatusResource;
use App\Http\Resources\Usage\UsageResource;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\VehicleCharacteristic\VehicleCharacteristicResource;
use App\Http\Resources\VehicleGenre\VehicleGenreResource;
use Illuminate\Http\Resources\Json\JsonResource;

class VehicleGenreUsageResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->hashId,
            'max_mileage_essence_per_year' => $this->max_mileage_essence_per_year,
            'max_mileage_diesel_per_year' => $this->max_mileage_diesel_per_year,
            'vehicle_genre' => new VehicleGenreResource($this->whenLoaded('vehicleGenre')),
            'usage' => new UsageResource($this->whenLoaded('usage')),
            // 'vehicle_characteristics' => VehicleCharacteristicResource::collection($this->whenLoaded('vehicleCharacteristics')),
            'status' => new StatusResource($this->whenLoaded('status')),
            'created_by' => new UserResource($this->whenLoaded('createdBy')),
            'updated_by' => new UserResource($this->whenLoaded('updatedBy')),
            'deleted_by' => new UserResource($this->whenLoaded('deletedBy')),
            'deleted_at' => dateTimeFormat($this->deleted_at),
            'created_at' => dateTimeFormat($this->created_at),
            'updated_at' => dateTimeFormat($this->updated_at),
        ];
    }
}
