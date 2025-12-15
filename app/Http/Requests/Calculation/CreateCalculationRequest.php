<?php

namespace App\Http\Requests\Calculation;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\VehicleCharacteristic;

class CreateCalculationRequest extends FormRequest
{
    public function prepareForValidation()
    {
        $this->merge([
            "vehicle_characteristics_id" => $this->vehicle_characteristics_id ? VehicleCharacteristic::keyFromHashId($this->vehicle_characteristics_id) : null,
        ]);
    }
    
    public function rules(): array
    {
        return [
            "license_plate" => "required|string|max:255",
            "mileage" => "required|numeric|min:0",
            "serial_number" => "required|string|max:255",
            "first_entry_into_circulation_date" => "required|date",
            "calculation_date" => "required|date",
            "insured" => "required|string|max:255",
            "vehicle_characteristics_id" => "required|exists:vehicle_characteristics,id",
        ];
    }

    public function messages(): array
    {
        return [
            "license_plate.required" => "La plaque d'immatriculation est requise.",
            "license_plate.string" => "La plaque d'immatriculation doit être une chaîne de caractères.",
            "license_plate.max" => "La plaque d'immatriculation doit contenir au plus 255 caractères.",
            "mileage.required" => "La distance parcourue est requise.",
            "mileage.numeric" => "La distance parcourue doit être un nombre.",
            "mileage.min" => "La distance parcourue doit être supérieure à 0.",
            "serial_number.required" => "Le numéro de série est requise.",
            "serial_number.string" => "Le numéro de série doit être une chaîne de caractères.",
            "serial_number.max" => "Le numéro de série doit contenir au plus 255 caractères.",
            "first_entry_into_circulation_date.required" => "La date de première mise en circulation est requise.",
            "first_entry_into_circulation_date.date" => "La date de première mise en circulation doit être une date valide.",
            "calculation_date.required" => "La date de calcul est requise.",
            "calculation_date.date" => "La date de calcul doit être une date valide.",
            "insured.required" => "L'assuré est requise.",
            "insured.string" => "L'assuré doit être une chaîne de caractères.",
            "insured.max" => "L'assuré doit contenir au plus 255 caractères.",
            "vehicle_characteristics_id.required" => "Les caractéristiques du véhicule sont requises.",
            "vehicle_characteristics_id.exists" => "Les caractéristiques du véhicule n'existent pas.",
        ];
    }
}
