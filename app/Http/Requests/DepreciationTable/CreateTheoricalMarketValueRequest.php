<?php

namespace App\Http\Requests\DepreciationTable;

use App\Models\VehicleCharacteristic;
use Illuminate\Foundation\Http\FormRequest;

class CreateTheoricalMarketValueRequest extends FormRequest
{
    public function prepareForValidation()
    {
        $this->merge([
            'vehicle_characteristic_id' => $this->vehicle_characteristic_id ? VehicleCharacteristic::keyFromHashId($this->vehicle_characteristic_id) : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'vehicle_characteristic_id' => 'required|exists:vehicle_characteristics,id',
            'vehicle_mileage' => 'required|integer|min:0',
            'first_entry_into_circulation_date' => 'required|date_format:Y-m-d|before:tomorrow',
            'expertise_date' => 'required|date_format:Y-m-d|after:first_entry_into_circulation_date|before:tomorrow',
        ];
    }

    public function messages(): array
    {
        return [
            'first_entry_into_circulation_date.date' => 'La date est invalide.',
            'first_entry_into_circulation_date.date_format' => 'Le format de la date est invalide.',
            'expertise_date.date' => 'La date est invalide.',
            'expertise_date.date_format' => 'Le format de la date est invalide.',
            'expertise_date.after' => 'La date d\'expertise doit être après la date de première mise en circulation.',
            'expertise_date.before' => 'La date d\'expertise doit être avant la date de demain.',
            'first_entry_into_circulation_date.after' => 'La date de première mise en circulation doit être après la date d\'aujourd\'hui.',
            'first_entry_into_circulation_date.before' => 'La date de première mise en circulation doit être avant la date de demain.',
            'vehicle_characteristic_id.required' => 'Les caractéristiques du véhicule sont requises.',
            'vehicle_characteristic_id.exists' => 'Les caractéristiques du véhicule sont invalides.',
            'vehicle_mileage.required' => 'Le kilométrage du véhicule est requis.',
            'vehicle_mileage.integer' => 'Le kilométrage du véhicule doit être un nombre entier.',
            'vehicle_mileage.min' => 'Le kilométrage du véhicule doit être supérieure ou égale à 0.',
        ];
    }
}
