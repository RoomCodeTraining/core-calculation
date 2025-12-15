<?php

namespace App\Http\Resources\Usage;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\VehicleGenre\VehicleGenreResource;
use App\Http\Resources\Status\StatusResource;
use App\Http\Resources\User\UserResource;

class UsageResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->hashId,
            'code' => $this->code,
            'label' => $this->label,
            'description' => $this->description,
            'vehicle_genre' => new VehicleGenreResource($this->whenLoaded('vehicleGenre')),
            'max_mileage_essence_per_year' => $this->max_mileage_essence_per_year,
            'max_mileage_diesel_per_year' => $this->max_mileage_diesel_per_year,
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
