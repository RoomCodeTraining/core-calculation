<?php

namespace App\Http\Services;

use Carbon\Carbon;
use App\Models\VehicleAge;
use App\Models\DepreciationTable;
use App\Models\Energy;
use App\Models\Usage;

class MarketValueService
{
    /**
     * Calcule la valeur théorique du marché d'un véhicule.
     *
     * Pour calculer la dépréciation, on a besoin de :
     * - L'usage (usage_id) : pour trouver la table de dépréciation correspondante
     * - L'âge du véhicule (calculé à partir des dates) : pour trouver la table de dépréciation correspondante
     * - La source d'énergie (energy_id) : utilisée dans le contrôleur pour calculer l'incidence kilométrique
     *
     * @param int $usage_id ID de l'usage du véhicule
     * @param int $energy_id ID de l'énergie du véhicule (essence/diesel)
     * @param float $vehicle_new_value Valeur neuve du véhicule
     * @param float $vehicle_mileage Kilométrage du véhicule
     * @param string $first_entry_into_circulation_date Date de première mise en circulation
     * @param string $expertise_date Date d'expertise
     * @return array
     */
    public function calculateTheoreticalMarketValue($usage_id, $energy_id, $vehicle_new_value, $vehicle_mileage, $first_entry_into_circulation_date, $expertise_date)
    {
        // Calcul de l'âge du véhicule en années et mois
        $year_diff = ceil(Carbon::parse($first_entry_into_circulation_date)->diffInYears($expertise_date));
        $month_diff = ceil(Carbon::parse($first_entry_into_circulation_date)->diffInMonths($expertise_date));

        // Récupération de l'usage (nécessaire pour trouver la table de dépréciation)
        // Note: usage_id dans DepreciationTable pointe vers genres, donc on utilise genre_id de l'usage
        $usage = Usage::select('id', 'genre_id', 'label', 'max_mileage_essence_per_year', 'max_mileage_diesel_per_year')->find($usage_id);

        // Le genre_id est utilisé pour chercher dans DepreciationTable car usage_id pointe vers genres
        $genre_id = $usage->genre_id ?? $usage_id;

        // Récupération de l'énergie (nécessaire pour le calcul de l'incidence kilométrique dans le contrôleur)
        $energy = Energy::select('id', 'label', 'code')->find($energy_id);

        // Récupération de l'âge du véhicule (nécessaire pour trouver la table de dépréciation)
        // L'âge du véhicule est en mois
        $vehicle_age = VehicleAge::firstWhere('value', $month_diff);

        if ($vehicle_age) {
            $month_diff = floatval(str_replace(',', '.', $month_diff));

            // Recherche de la table de dépréciation en utilisant l'usage ET l'âge du véhicule
            if ($month_diff <= 60) {
                // Pour les véhicules de moins de 60 mois, recherche directe dans la table
                $depreciation_table = DepreciationTable::where('usage_id', $genre_id)
                    ->where('vehicle_age_id', $vehicle_age->id)
                    ->first();
                $theorical_depreciation_rate = $depreciation_table->value ?? 0;
            } elseif ($month_diff > 60 && $month_diff <= 84) {
                if ($month_diff > 60 && $month_diff <= 66) {
                    $vehicle_age_min = VehicleAge::firstWhere('value', 60);
                    $depreciation_table_min = DepreciationTable::where('usage_id', $genre_id)
                        ->where('vehicle_age_id', $vehicle_age_min->id)
                        ->first()->value ?? 0;

                    $vehicle_age_max = VehicleAge::firstWhere('value', 66);
                    $depreciation_table_max = DepreciationTable::where('usage_id', $genre_id)
                        ->where('vehicle_age_id', $vehicle_age_max->id)
                        ->first()->value ?? 0;
                } elseif ($month_diff > 66 && $month_diff <= 72) {
                    $vehicle_age_min = VehicleAge::firstWhere('value', 66);
                    $depreciation_table_min = DepreciationTable::where('usage_id', $genre_id)
                        ->where('vehicle_age_id', $vehicle_age_min->id)
                        ->first()->value ?? 0;

                    $vehicle_age_max = VehicleAge::firstWhere('value', 72);
                    $depreciation_table_max = DepreciationTable::where('usage_id', $genre_id)
                        ->where('vehicle_age_id', $vehicle_age_max->id)
                        ->first()->value ?? 0;
                } elseif ($month_diff > 72 && $month_diff <= 78) {
                    $vehicle_age_min = VehicleAge::firstWhere('value', 72);
                    $depreciation_table_min = DepreciationTable::where('usage_id', $genre_id)
                        ->where('vehicle_age_id', $vehicle_age_min->id)
                        ->first()->value ?? 0;

                    $vehicle_age_max = VehicleAge::firstWhere('value', 78);
                    $depreciation_table_max = DepreciationTable::where('usage_id', $genre_id)
                        ->where('vehicle_age_id', $vehicle_age_max->id)
                        ->first()->value ?? 0;
                } elseif ($month_diff > 78 && $month_diff <= 84) {
                    $vehicle_age_min = VehicleAge::firstWhere('value', 78);
                    $depreciation_table_min = DepreciationTable::where('usage_id', $genre_id)
                        ->where('vehicle_age_id', $vehicle_age_min->id)
                        ->first()->value ?? 0;

                    $vehicle_age_max = VehicleAge::firstWhere('value', 84);
                    $depreciation_table_max = DepreciationTable::where('usage_id', $genre_id)
                        ->where('vehicle_age_id', $vehicle_age_max->id)
                        ->first()->value ?? 0;
                }

                $theorical_depreciation_rate = number_format(
                    (((($depreciation_table_max - $depreciation_table_min) * ($vehicle_age->value - $vehicle_age_min->value)) / 6) + $depreciation_table_min),
                    2,
                    ',',
                    ''
                );
                $theorical_depreciation_rate = floatval(str_replace(',', '.', $theorical_depreciation_rate));
            } elseif ($month_diff > 84 && $month_diff <= 120) {
                if ($month_diff > 84 && $month_diff <= 96) {
                    $vehicle_age_min = VehicleAge::firstWhere('value', 84);
                    $depreciation_table_min = DepreciationTable::where('usage_id', $genre_id)
                        ->where('vehicle_age_id', $vehicle_age_min->id)
                        ->first()->value ?? 0;

                    $vehicle_age_max = VehicleAge::firstWhere('value', 96);
                    $depreciation_table_max = DepreciationTable::where('usage_id', $genre_id)
                        ->where('vehicle_age_id', $vehicle_age_max->id)
                        ->first()->value ?? 0;
                } elseif ($month_diff > 96 && $month_diff <= 108) {
                    $vehicle_age_min = VehicleAge::firstWhere('value', 96);
                    $depreciation_table_min = DepreciationTable::where('usage_id', $genre_id)
                        ->where('vehicle_age_id', $vehicle_age_min->id)
                        ->first()->value ?? 0;

                    $vehicle_age_max = VehicleAge::firstWhere('value', 108);
                    $depreciation_table_max = DepreciationTable::where('usage_id', $genre_id)
                        ->where('vehicle_age_id', $vehicle_age_max->id)
                        ->first()->value ?? 0;
                } elseif ($month_diff > 108 && $month_diff <= 120) {
                    $vehicle_age_min = VehicleAge::firstWhere('value', 108);
                    $depreciation_table_min = DepreciationTable::where('usage_id', $genre_id)
                        ->where('vehicle_age_id', $vehicle_age_min->id)
                        ->first()->value ?? 0;

                    $vehicle_age_max = VehicleAge::firstWhere('value', 120);
                    $depreciation_table_max = DepreciationTable::where('usage_id', $genre_id)
                        ->where('vehicle_age_id', $vehicle_age_max->id)
                        ->first()->value ?? 0;
                }

                $theorical_depreciation_rate = number_format(
                    (((($depreciation_table_max - $depreciation_table_min) * ($vehicle_age->value - $vehicle_age_min->value)) / 12) + $depreciation_table_min),
                    2,
                    ',',
                    ''
                );
                $theorical_depreciation_rate = floatval(str_replace(',', '.', $theorical_depreciation_rate));
            }

            $vehicle_age = $vehicle_age->value;
            $theorical_vehicle_market_value = ceil($vehicle_new_value - ($vehicle_new_value * $theorical_depreciation_rate / 100));

            return [
                'expertise_date' => $expertise_date,
                'first_entry_into_circulation_date' => $first_entry_into_circulation_date,
                'vehicle_new_value' => $vehicle_new_value,
                'year_diff' => $year_diff,
                'month_diff' => $month_diff,
                'vehicle_age' => $vehicle_age,
                'theorical_depreciation_rate' => $theorical_depreciation_rate,
                'theorical_vehicle_market_value' => $theorical_vehicle_market_value,
                'usage' => $usage,
                'energy' => $energy,
            ];
        } else {
            return [
                'expertise_date' => $expertise_date,
                'first_entry_into_circulation_date' => $first_entry_into_circulation_date,
                'vehicle_new_value' => $vehicle_new_value,
                'year_diff' => $year_diff,
                'month_diff' => $month_diff,
                'vehicle_age' => 0,
                'theorical_depreciation_rate' => 0,
                'theorical_vehicle_market_value' => 0,
                'usage' => $usage,
                'energy' => $energy,
            ];
        }
    }
}
