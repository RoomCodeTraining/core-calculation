<?php

namespace App\Http\Requests\DepreciationTable;

use App\Models\VehicleGenreUsage;
use App\Models\VehicleCharacteristic;
use App\Models\VehicleEnergy;
use Illuminate\Foundation\Http\FormRequest;

class CreateTheoricalMarketValueRequest extends FormRequest
{
    public function prepareForValidation()
    {
        $this->merge([
            'vehicle_characteristic_id' => $this->vehicle_characteristic_id ? VehicleCharacteristic::keyFromHashId($this->vehicle_characteristic_id) : null,
            // 'vehicle_energy_id' => $this->vehicle_energy_id ? VehicleEnergy::keyFromHashId($this->vehicle_energy_id) : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'vehicle_characteristic_id' => 'required|exists:vehicle_characteristics,id',
            // 'vehicle_energy_id' => 'required|exists:vehicle_energies,id',
            'vehicle_new_value' => 'required|integer|min:0',
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
            'vehicle_energy_id.required' => 'L\'énergie du véhicule est requise.',
            'vehicle_energy_id.exists' => 'L\'énergie du véhicule est invalide.',
            'vehicle_new_value.required' => 'La valeur neuve du véhicule est requise.',
            'vehicle_new_value.integer' => 'La valeur neuve du véhicule doit être un nombre entier.',
            'vehicle_new_value.min' => 'La valeur neuve du véhicule doit être supérieure ou égale à 0.',
            'vehicle_mileage.required' => 'Le kilométrage du véhicule est requis.',
            'vehicle_mileage.integer' => 'Le kilométrage du véhicule doit être un nombre entier.',
            'vehicle_mileage.min' => 'Le kilométrage du véhicule doit être supérieure ou égale à 0.',
        ];
    }
}
