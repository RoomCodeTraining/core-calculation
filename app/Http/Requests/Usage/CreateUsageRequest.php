<?php

namespace App\Http\Requests\Usage;

use App\Models\VehicleGenre;
use Illuminate\Foundation\Http\FormRequest;

class CreateUsageRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "label" => "required|string|max:255",
            "description" => "nullable|string|max:255",
            "max_mileage_essence_per_year" => "nullable|numeric|min:0",
            "max_mileage_diesel_per_year" => "nullable|numeric|min:0",
        ];
    }

    public function messages(): array
    {
        return [
            "label.required" => "Le label est requis.",
            "label.string" => "Le label doit être une chaîne de caractères.",
            "label.max" => "Le label doit contenir au plus 255 caractères.",
            "description.string" => "La description doit être une chaîne de caractères.",
            "description.max" => "La description doit contenir au plus 255 caractères.",
            "max_mileage_essence_per_year.numeric" => "La distance maximale d'essence par an doit être un nombre.",
            "max_mileage_essence_per_year.min" => "La distance maximale d'essence par an doit être supérieure à 0.",
            "max_mileage_diesel_per_year.numeric" => "La distance maximale de diesel par an doit être un nombre.",
            "max_mileage_diesel_per_year.min" => "La distance maximale de diesel par an doit être supérieure à 0.",
        ];
    }
}
