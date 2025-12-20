<?php

namespace App\Http\Resources\VehicleCharacteristic;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Price\PriceResource;
use App\Http\Resources\Usage\UsageResource;
use App\Http\Resources\VehicleEnergy\VehicleEnergyResource;
use App\Http\Resources\Dealer\DealerResource;
use App\Http\Resources\Status\StatusResource;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\VehicleModel\VehicleModelResource;
use App\Http\Resources\VehicleGenreUsage\VehicleGenreUsageResource;

class VehicleCharacteristicResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->hashId,
            'type' => $this->type,
            'options' => $this->options,
            'fiscal_power' => $this->fiscal_power,
            'nb_seats' => $this->nb_seats,
            'prices' => PriceResource::collection($this->whenLoaded('prices')),
            'vehicle_model' => new VehicleModelResource($this->whenLoaded('vehicleModel')),
            'vehicle_genre_usage' => new VehicleGenreUsageResource($this->whenLoaded('vehicleGenreUsage')),
            'usage' => new UsageResource($this->whenLoaded('usage')),
            'vehicle_energy' => new VehicleEnergyResource($this->whenLoaded('vehicleEnergy')),
            'dealer' => new DealerResource($this->whenLoaded('dealer')),
            'status' => new StatusResource($this->whenLoaded('status')),
            'created_by' => new UserResource($this->whenLoaded('createdBy')),
            'updated_by' => new UserResource($this->whenLoaded('updatedBy')),
            'deleted_by' => new UserResource($this->whenLoaded('deletedBy')),
            'created_at' => dateTimeFormat($this->created_at),
            'updated_at' => dateTimeFormat($this->updated_at),
        ];
    }
}
