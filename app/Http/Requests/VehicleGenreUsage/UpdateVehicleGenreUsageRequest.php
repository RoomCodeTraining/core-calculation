<?php

namespace App\Http\Requests\VehicleGenreUsage;

use App\Models\Usage;
use App\Models\VehicleCharacteristic;
use App\Models\VehicleGenre;
use Illuminate\Foundation\Http\FormRequest;

class UpdateVehicleGenreUsageRequest extends FormRequest
{
    public function prepareForValidation()
    {
        $vehicleCharacteristicIds = collect($this->vehicle_characteristic_ids ?? [])
            ->filter()
            ->map(fn ($id) => VehicleCharacteristic::keyFromHashId($id))
            ->values()
            ->all();

        $this->merge([
            "vehicle_genre_id" => $this->vehicle_genre_id ? VehicleGenre::keyFromHashId($this->vehicle_genre_id) : null,
            "usage_id" => $this->usage_id ? Usage::keyFromHashId($this->usage_id) : null,
            "vehicle_characteristic_ids" => $vehicleCharacteristicIds,
        ]);
    }

    public function rules(): array
    {
        return [
            "vehicle_genre_id" => "required|exists:vehicle_genres,id",
            "usage_id" => "required|exists:usages,id",
            "max_mileage_essence_per_year" => "nullable|numeric|min:0",
            "max_mileage_diesel_per_year" => "nullable|numeric|min:0",
            "vehicle_characteristic_ids" => "nullable|array",
            "vehicle_characteristic_ids.*" => "required|exists:vehicle_characteristics,id",
        ];
    }

    public function messages(): array
    {
        return [
            "vehicle_genre_id.required" => "Le genre de véhicule est requis.",
            "vehicle_genre_id.exists" => "Le genre de véhicule n'existe pas.",
            "usage_id.required" => "L'usage est requis.",
            "usage_id.exists" => "L'usage n'existe pas.",
            "max_mileage_essence_per_year.numeric" => "La distance maximale d'essence par an doit être un nombre.",
            "max_mileage_essence_per_year.min" => "La distance maximale d'essence par an doit être supérieure à 0.",
            "max_mileage_diesel_per_year.numeric" => "La distance maximale de diesel par an doit être un nombre.",
            "max_mileage_diesel_per_year.min" => "La distance maximale de diesel par an doit être supérieure à 0.",
            "vehicle_characteristic_ids.array" => "Les caractéristiques de véhicule doivent être un tableau.",
            "vehicle_characteristic_ids.*.required" => "La caractéristique de véhicule est requise.",
            "vehicle_characteristic_ids.*.exists" => "La caractéristique de véhicule n'existe pas.",
        ];
    }
}
