<?php

namespace App\Http\Requests\VehicleCharacteristic;

use App\Models\VehicleEnergy;
use App\Models\Dealer;
use App\Models\VehicleModel;
use App\Models\VehicleGenreUsage;
use Illuminate\Foundation\Http\FormRequest;

class CreateVehicleCharacteristicRequest extends FormRequest
{
    public function prepareForValidation()
    {
        $this->merge([
            "vehicle_model_id" => $this->vehicle_model_id ? VehicleModel::keyFromHashId($this->vehicle_model_id) : null,
            "vehicle_genre_usage_id" => $this->vehicle_genre_usage_id ? VehicleGenreUsage::keyFromHashId($this->vehicle_genre_usage_id) : null,
            "vehicle_energy_id" => $this->vehicle_energy_id ? VehicleEnergy::keyFromHashId($this->vehicle_energy_id) : null,
            "dealer_id" => $this->dealer_id ? Dealer::keyFromHashId($this->dealer_id) : null,
        ]);
    }

    public function rules(): array
    {
        return [
            "vehicle_model_id" => "required|exists:vehicle_models,id",
            "vehicle_genre_usage_id" => "required|exists:vehicle_genre_usages,id",
            "vehicle_energy_id" => "required|exists:vehicle_energies,id",
            "dealer_id" => "required|exists:dealers,id",
            "type" => "nullable|string|max:255",
            "options" => "nullable|string|max:255",
            "fiscal_power" => "nullable|integer|min:0",
            "nb_seats" => "nullable|integer",
            "new_market_value" => "nullable|numeric|min:0",
            "date" => "required|date_format:Y-m-d|before:tomorrow",
        ];
    }

    public function messages(): array
    {
        return [
            "vehicle_model_id.required" => "Le modèle de véhicule est requis.",
            "vehicle_model_id.exists" => "Le modèle de véhicule n'existe pas.",
            "vehicle_genre_usage_id.required" => "Le genre de véhicule est requis.",
            "vehicle_genre_usage_id.exists" => "Le genre de véhicule n'existe pas.",
            "vehicle_energy_id.required" => "L'énergie est requise.",
            "vehicle_energy_id.exists" => "L'énergie n'existe pas.",
            "dealer_id.required" => "Le concessionnaire est requis.",
            "dealer_id.exists" => "Le concessionnaire n'existe pas.",
            "type.string" => "Le type doit être une chaîne de caractères.",
            "type.max" => "Le type doit contenir au plus 255 caractères.",
            "options.string" => "Les options doivent être une chaîne de caractères.",
            "options.max" => "Les options doivent contenir au plus 255 caractères.",
            "fiscal_power.integer" => "La puissance fiscale doit être un nombre entier.",
            "fiscal_power.min" => "La puissance fiscale doit être supérieure à 0.",
            "nb_seats.integer" => "Le nombre de places doit être un nombre entier.",
            "new_market_value.numeric" => "La valeur neuve doit être un nombre.",
            "new_market_value.min" => "La valeur neuve doit être supérieure à 0.",
            "date.required" => "La date est requise.",
            "date.date" => "La date doit être une date valide.",
            "date.before" => "La date doit être antérieure à demain.",
        ];
    }
}
