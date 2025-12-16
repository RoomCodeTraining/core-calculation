<?php

namespace App\Http\Requests\VehicleGenreUsage;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\VehicleGenre;
use App\Models\Usage;

class CreateVehicleGenreUsageRequest extends FormRequest
{
    public function prepareForValidation()
    {
        $this->merge([
            "vehicle_genre_id" => $this->vehicle_genre_id ? VehicleGenre::keyFromHashId($this->vehicle_genre_id) : null,
            "usage_id" => $this->usage_id ? Usage::keyFromHashId($this->usage_id) : null,
        ]);
    }

    public function rules(): array
    {
        return [
            "vehicle_genre_id" => "required|exists:vehicle_genres,id",
            "usage_id" => "required|exists:usages,id",
            "max_mileage_essence_per_year" => "required|numeric|min:0",
            "max_mileage_diesel_per_year" => "required|numeric|min:0",
        ];
    }

    public function messages(): array
    {
        return [
            "vehicle_genre_id.required" => "Le genre de véhicule est requis.",
            "vehicle_genre_id.exists" => "Le genre de véhicule n'existe pas.",
            "usage_id.required" => "L'usage est requis.",
            "usage_id.exists" => "L'usage n'existe pas.",
            "max_mileage_essence_per_year.required" => "La distance maximale d'essence par an est requise.",
            "max_mileage_essence_per_year.numeric" => "La distance maximale d'essence par an doit être un nombre.",
            "max_mileage_essence_per_year.min" => "La distance maximale d'essence par an doit être supérieure à 0.",
            "max_mileage_diesel_per_year.required" => "La distance maximale de diesel par an est requise.",
            "max_mileage_diesel_per_year.numeric" => "La distance maximale de diesel par an doit être un nombre.",
            "max_mileage_diesel_per_year.min" => "La distance maximale de diesel par an doit être supérieure à 0.",
        ];
    }
}
