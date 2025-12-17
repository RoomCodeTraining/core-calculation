<?php

namespace App\Http\Resources\Calculation;

use App\Http\Resources\User\UserResource;
use App\Http\Resources\Entity\EntityResource;
use App\Http\Resources\Status\StatusResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\VehicleCharacteristic\VehicleCharacteristicResource;

class CalculationResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->hashId,
            'reference' => $this->reference,
            'license_plate' => $this->license_plate,
            'mileage' => $this->mileage,
            'serial_number' => $this->serial_number,
            'first_entry_into_circulation_date' => $this->first_entry_into_circulation_date,
            'calculation_date' => $this->calculation_date,
            'insured' => $this->insured,
            'evaluation' => json_decode($this->evaluation),
            'vehicle_characteristic' => new VehicleCharacteristicResource($this->whenLoaded('vehicleCharacteristic')),
            'entity' => new EntityResource($this->whenLoaded('entity')),
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
