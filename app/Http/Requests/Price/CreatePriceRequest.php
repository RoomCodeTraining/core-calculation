<?php

namespace App\Http\Requests\Price;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\VehicleCharacteristic;

class CreatePriceRequest extends FormRequest
{
    public function prepareForValidation()
    {
        $this->merge([
            "vehicle_characteristic_id" => $this->vehicle_characteristic_id ? VehicleCharacteristic::keyFromHashId($this->vehicle_characteristic_id) : null,
        ]);
    }

    public function rules(): array
    {
        return [
            "value" => "required|numeric|min:0",
            "date" => "required|date_format:Y-m-d|before:tomorrow",
            "vehicle_characteristic_id" => "required|exists:vehicle_characteristics,id",
        ];
    }

    public function messages(): array
    {
        return [
            "value.required" => "La valeur est requise.",
            "value.numeric" => "La valeur doit être un nombre.",
            "value.min" => "La valeur doit être supérieure à 0.",
            "date.required" => "La date est requise.",
            "date.date_format" => "Le format de la date est invalide.",
            "date.before" => "La date doit être avant la date du jour.",
            "vehicle_characteristic_id.required" => "Les caractéristiques du véhicule sont requises.",
            "vehicle_characteristic_id.exists" => "Les caractéristiques du véhicule sont invalides.",
        ];
    }
}
