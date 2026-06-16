<?php

namespace App\Http\Requests\VehicleCharacteristicGenreUsage;

use App\Models\VehicleCharacteristic;
use App\Models\VehicleCharacteristicGenreUsage;
use App\Models\VehicleGenreUsage;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateVehicleCharacteristicGenreUsageRequest extends FormRequest
{
    public function prepareForValidation(): void
    {
        $this->merge([
            'vehicle_characteristic_id' => $this->vehicle_characteristic_id
                ? VehicleCharacteristic::keyFromHashId($this->vehicle_characteristic_id)
                : null,
            'vehicle_genre_usage_id' => $this->vehicle_genre_usage_id
                ? VehicleGenreUsage::keyFromHashId($this->vehicle_genre_usage_id)
                : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'vehicle_characteristic_id' => 'required|exists:vehicle_characteristics,id',
            'vehicle_genre_usage_id' => [
                'required',
                'exists:vehicle_genre_usages,id',
                Rule::unique('vehicle_characteristic_genre_usages', 'vehicle_genre_usage_id')
                    ->where(fn ($query) => $query->where('vehicle_characteristic_id', $this->vehicle_characteristic_id)),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'vehicle_characteristic_id.required' => 'La caractéristique de véhicule est requise.',
            'vehicle_characteristic_id.exists' => 'La caractéristique de véhicule n\'existe pas.',
            'vehicle_genre_usage_id.required' => 'Le genre/usage de véhicule est requis.',
            'vehicle_genre_usage_id.exists' => 'Le genre/usage de véhicule n\'existe pas.',
            'vehicle_genre_usage_id.unique' => 'Cette association existe déjà.',
        ];
    }
}
