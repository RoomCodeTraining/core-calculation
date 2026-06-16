<?php

namespace App\Http\Resources\VehicleCharacteristicGenreUsage;

use App\Http\Resources\Status\StatusResource;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\VehicleCharacteristic\VehicleCharacteristicResource;
use App\Http\Resources\VehicleGenreUsage\VehicleGenreUsageResource;
use Illuminate\Http\Resources\Json\JsonResource;

class VehicleCharacteristicGenreUsageResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->hashId,
            'vehicle_characteristic' => new VehicleCharacteristicResource($this->whenLoaded('vehicleCharacteristic')),
            'vehicle_genre_usage' => new VehicleGenreUsageResource($this->whenLoaded('vehicleGenreUsage')),
            'status' => new StatusResource($this->whenLoaded('status')),
            'created_by' => new UserResource($this->whenLoaded('createdBy')),
            'updated_by' => new UserResource($this->whenLoaded('updatedBy')),
            'deleted_by' => new UserResource($this->whenLoaded('deletedBy')),
        ];
    }
}
