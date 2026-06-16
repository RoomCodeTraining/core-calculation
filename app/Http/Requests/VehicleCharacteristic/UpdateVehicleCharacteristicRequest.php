<?php

namespace App\Http\Requests\VehicleCharacteristic;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\VehicleEnergy;
use App\Models\Dealer;
use App\Models\VehicleModel;
use App\Models\VehicleGenreUsage;

class UpdateVehicleCharacteristicRequest extends FormRequest
{
    public function prepareForValidation()
    {
        $vehicleGenreUsageIds = collect($this->vehicle_genre_usage_ids ?? [])
            ->filter()
            ->map(fn ($id) => VehicleGenreUsage::keyFromHashId($id))
            ->values()
            ->all();

        $this->merge([
            "vehicle_model_id" => $this->vehicle_model_id ? VehicleModel::keyFromHashId($this->vehicle_model_id) : null,
            "vehicle_genre_usage_ids" => $vehicleGenreUsageIds,
            "vehicle_energy_id" => $this->vehicle_energy_id ? VehicleEnergy::keyFromHashId($this->vehicle_energy_id) : null,
            "dealer_id" => $this->dealer_id ? Dealer::keyFromHashId($this->dealer_id) : null,
        ]);
    }
    public function rules(): array
    {
        return [
            "vehicle_model_id" => "required|exists:vehicle_models,id",
            "vehicle_genre_usage_ids" => "required|array|min:1",
            "vehicle_genre_usage_ids.*" => "required|exists:vehicle_genre_usages,id",
            "vehicle_energy_id" => "required|exists:vehicle_energies,id",
            "dealer_id" => "required|exists:dealers,id",
            "type" => "nullable|string|max:255",
            "equipments" => "nullable|string",
            "options" => "nullable|string",
            "fiscal_power" => "nullable|integer|min:0",
            "nb_seats" => "nullable|integer",
            "new_market_value" => "nullable|numeric|min:0",
        ];
    }

    public function messages(): array
    {
        return [
            "vehicle_model_id.required" => "Le modèle de véhicule est requis.",
            "vehicle_model_id.exists" => "Le modèle de véhicule n'existe pas.",
            "vehicle_genre_usage_ids.required" => "Les genres de véhicule sont requis.",
            "vehicle_genre_usage_ids.array" => "Les genres de véhicule doivent être un tableau.",
            "vehicle_genre_usage_ids.min" => "Au moins un genre de véhicule est requis.",
            "vehicle_genre_usage_ids.*.required" => "Le genre de véhicule est requis.",
            "vehicle_genre_usage_ids.*.exists" => "Le genre de véhicule n'existe pas.",
            "vehicle_energy_id.required" => "L'énergie est requise.",
            "vehicle_energy_id.exists" => "L'énergie n'existe pas.",
            "dealer_id.required" => "Le concessionnaire est requis.",
            "dealer_id.exists" => "Le concessionnaire n'existe pas.",
            "type.string" => "Le type doit être une chaîne de caractères.",
            "type.max" => "Le type doit contenir au plus 255 caractères.",
            "equipments.string" => "Les équipements doivent être une chaîne de caractères.",
            "options.string" => "Les options doivent être une chaîne de caractères.",
            "fiscal_power.integer" => "La puissance fiscale doit être un nombre entier.",
            "fiscal_power.min" => "La puissance fiscale doit être supérieure à 0.",
            "nb_seats.integer" => "Le nombre de places doit être un nombre entier.",
            "new_market_value.numeric" => "La valeur neuve doit être un nombre.",
            "new_market_value.min" => "La valeur neuve doit être supérieure à 0.",
        ];
    }
}
