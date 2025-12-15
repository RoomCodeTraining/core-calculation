<?php

namespace App\Http\Requests\VehicleGenreUsage;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\VehicleGenre;
use App\Models\Usage;

class UpdateVehicleGenreUsageRequest extends FormRequest
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
        ];
    }

    public function messages(): array
    {
        return [
            "vehicle_genre_id.required" => "Le genre de véhicule est requis.",
            "vehicle_genre_id.exists" => "Le genre de véhicule n'existe pas.",
            "usage_id.required" => "L'usage est requis.",
            "usage_id.exists" => "L'usage n'existe pas.",
        ];
    }
}
