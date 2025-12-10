<?php

namespace App\Http\Controllers\API;

use App\Filters\DepreciationTableFilters;
use App\Http\Controllers\Controller;
use App\Http\Resources\DepreciationTableResource;
use App\Http\Resources\EnergyResource;
use App\Http\Resources\UsageResource;
use App\Http\Services\MarketValueService;
use App\Models\DepreciationTable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DepreciationTableController extends Controller
{
    /**
     * Display a listing of the depreciation tables.
     */
    public function index(): JsonResponse
    {
        $depreciationTables = DepreciationTable::useFilters(DepreciationTableFilters::class)
            ->with(['genre', 'vehicleAge'])
            ->paginate();

        return $this->responseSuccess(
            'Depreciation tables retrieved successfully',
            DepreciationTableResource::collection($depreciationTables)->response()->getData(true)
        );
    }

    /**
     * Display the specified depreciation table.
     */
    public function show(Request $request, string $id): JsonResponse
    {
        $depreciationTable = DepreciationTable::findByHashId($id);

        if (!$depreciationTable) {
            return $this->responseNotFound('Depreciation table not found');
        }

        $depreciationTable->load(['genre', 'vehicleAge']);

        return $this->responseSuccess('Depreciation table retrieved successfully', new DepreciationTableResource($depreciationTable));
    }

    /**
     * Calcule la valeur théorique du marché et la dépréciation.
     *
     * Paramètres requis pour le calcul :
     * - usage_id : L'usage du véhicule (nécessaire pour trouver la table de dépréciation)
     * - energy_id : La source d'énergie du véhicule (essence/diesel) pour calculer l'incidence kilométrique
     * - vehicle_new_value : La valeur neuve du véhicule
     * - vehicle_mileage : Le kilométrage actuel du véhicule
     * - first_entry_into_circulation_date : Date de première mise en circulation (pour calculer l'âge)
     * - expertise_date : Date d'expertise (pour calculer l'âge)
     * - market_incidence_rate : Taux d'incidence de marché (optionnel, 0-100)
     *
     * Pour trouver la table de dépréciation, on utilise :
     * - L'usage (usage_id)
     * - L'âge du véhicule (calculé à partir des dates first_entry_into_circulation_date et expertise_date)
     * - La source d'énergie (energy_id) est utilisée pour le calcul de l'incidence kilométrique
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'usage_id' => 'required|string',
            'energy_id' => 'required|string',
            'vehicle_new_value' => 'required|numeric|min:0',
            'vehicle_mileage' => 'required|integer|min:0',
            'first_entry_into_circulation_date' => 'required|date|before_or_equal:expertise_date',
            'expertise_date' => 'required|date|after_or_equal:first_entry_into_circulation_date',
            'market_incidence_rate' => 'nullable|numeric|min:0|max:100',
        ], [
            'first_entry_into_circulation_date.before_or_equal' => 'La date de première mise en circulation doit être antérieure ou égale à la date d\'expertise.',
            'expertise_date.after_or_equal' => 'La date d\'expertise doit être postérieure ou égale à la date de première mise en circulation.',
            'vehicle_mileage.integer' => 'Le kilométrage doit être un nombre entier.',
        ]);

        // Convertir le hash ID de l'usage en ID numérique et charger les relations
        $usage = \App\Models\Usage::with(['genre.vehicleModel.brand'])->findByHashId($request->usage_id);
        if (!$usage) {
            return $this->responseNotFound('Usage not found');
        }
        $usageId = $usage->id;

        // Convertir le hash ID de l'énergie en ID numérique
        $energy = \App\Models\Energy::findByHashId($request->energy_id);
        if (!$energy) {
            return $this->responseNotFound('Energy not found');
        }
        $energyId = $energy->id;

        $marketValueService = app(MarketValueService::class);
        // Le service utilise usage_id, energy_id et calcule l'âge à partir des dates
        $result = $marketValueService->calculateTheoreticalMarketValue(
            $usageId,
            $energyId,
            $request->vehicle_new_value,
            $request->vehicle_mileage,
            $request->first_entry_into_circulation_date,
            $request->expertise_date
        );

        $result = (object) $result;

        // Calcul de l'incidence kilométrique en utilisant l'énergie (essence ou diesel)
        $kilometric_incidence = 0;
        $is_up = null;

        // Utilisation de l'énergie pour déterminer le calcul (essence = VE01)
        if ($result->energy && $result->energy->code == 'VE01') {
            $max_mileage_essence_per_month = $result->usage->max_mileage_essence_per_year / 12;
            $kilometric_incidence = (($max_mileage_essence_per_month * $result->month_diff) - $request->vehicle_mileage) * 25;
        } else {
            $max_mileage_diesel_per_month = $result->usage->max_mileage_diesel_per_year / 12;
            $kilometric_incidence = (($max_mileage_diesel_per_month * $result->month_diff) - $request->vehicle_mileage) * 40;
        }

        if ($kilometric_incidence > 0) {
            $is_up = true;
        } else {
            $is_up = false;
        }

        $market_incidence_rate = $request->market_incidence_rate ?? 0;
        $market_incidence = ceil($result->vehicle_new_value * $market_incidence_rate / 100);

        if ($kilometric_incidence > $result->theorical_vehicle_market_value) {
            $kilometric_incidence = $result->theorical_vehicle_market_value / 2;
        }

        $vehicle_market_value = $result->theorical_vehicle_market_value + $market_incidence + $kilometric_incidence;
        $depreciation_rate = $result->vehicle_new_value > 0
            ? number_format(100 - ($vehicle_market_value * 100 / $result->vehicle_new_value), 2, ',', '')
            : 0;
        $depreciation_rate = floatval(str_replace(',', '.', $depreciation_rate));

        // Recharger l'usage avec toutes les relations pour la réponse
        $usage->load(['genre.vehicleModel.brand']);

        $result = [
            // Informations sur les dates et l'âge
            'expertise_date' => $result->expertise_date,
            'first_entry_into_circulation_date' => $result->first_entry_into_circulation_date,
            'year_diff' => $result->year_diff,
            'month_diff' => $result->month_diff,
            'vehicle_age' => $result->vehicle_age,

            // Informations sur le véhicule
            'vehicle_new_value' => $result->vehicle_new_value,
            'vehicle_mileage' => $request->vehicle_mileage,

            // Calculs de dépréciation
            'theorical_depreciation_rate' => $result->theorical_depreciation_rate,
            'theorical_vehicle_market_value' => $result->theorical_vehicle_market_value,
            'depreciation_rate' => $depreciation_rate,
            'vehicle_market_value' => ceil($result->vehicle_new_value - ($result->vehicle_new_value * $depreciation_rate / 100)),

            // Incidences
            'is_up' => $is_up,
            'market_incidence_rate' => $market_incidence_rate,
            'market_incidence' => $market_incidence,
            'kilometric_incidence' => $kilometric_incidence,

            // Informations sur l'usage (avec genre, modèle et marque)
            'usage' => new UsageResource($usage),

            // Informations sur l'énergie
            'energy' => new EnergyResource($energy),
        ];

        return $this->responseSuccess('Depreciation calculated successfully', $result);
    }
}

