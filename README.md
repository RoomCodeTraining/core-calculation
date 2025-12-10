-   Kilometrage en dessous d'usage
-   kilometrage en entier
-   usage_id
-   description optionnel

VehicleAge::create([
'value' => $i,
'label' => $i . ' mois',
'description' => $i . ' mois',
]);

DepreciationTable::create([
'value' => 1.66,
'usage_id' => Genre::where('code', 'VG01')->first()->id,
'vehicle_age_id' => VehicleAge::where('value', 1)->first()->id,
]);

Usage::create([
'code' => 'VG01',
'max_mileage_essence_per_year' => '5000.00',
'max_mileage_diesel_per_year' => '5000.00',
'label' => 'CYCLO MOTO 5000 Km/An',
'description' => 'CYCLO MOTO 5000 Km/An',
]);

// c
public function calculate_theoretical_market_value(Request $request): JsonResponse
    {
        $marketValueService = app(MarketValueService::class);
        $result = $marketValueService->calculateTheoreticalMarketValue($request->vehicle_genre_id, $request->vehicle_energy_id, $request->vehicle_new_value, $request->vehicle_mileage, $request->first_entry_into_circulation_date, $request->expertise_date);
$result = (object) $result;

        $kilometric_incidence = 0;
        $is_up = null;
        if ($result->vehicle_energy && $result->vehicle_energy->code == 'VE01') {
            $max_mileage_essence_per_month = $result->vehicle_genre->max_mileage_essence_per_year / 12;
            $kilometric_incidence = (($max_mileage_essence_per_month * $result->month_diff) - $request->vehicle_mileage) * 25;
        } else {
            $max_mileage_diesel_per_month = $result->vehicle_genre->max_mileage_diesel_per_year / 12;
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
        $depreciation_rate = $result->vehicle_new_value > 0 ? number_format(100 - ($vehicle_market_value * 100 / $result->vehicle_new_value), 2, ',', '') : 0;
        $depreciation_rate = floatval(str_replace(',', '.', $depreciation_rate));

        $result = [
            'expertise_date' => $result->expertise_date,
            'first_entry_into_circulation_date' => $result->first_entry_into_circulation_date,
            'vehicle_new_value' => $result->vehicle_new_value,
            'year_diff' => $result->year_diff,
            'month_diff' => $result->month_diff,
            'vehicle_age' => $result->vehicle_age,
            'theorical_depreciation_rate' => $result->theorical_depreciation_rate,
            'theorical_vehicle_market_value' => $result->theorical_vehicle_market_value,
            'is_up' => $is_up,
            'market_incidence_rate' => $market_incidence_rate,
            'market_incidence' => $market_incidence,
            'kilometric_incidence' => $kilometric_incidence,
            'depreciation_rate' => $depreciation_rate,
            'vehicle_market_value' => ceil($result->vehicle_new_value - ($result->vehicle_new_value * $depreciation_rate / 100))
        ];

        return $this->responseSuccess('DepreciationTable created successfully', $result);
    }

// pour calculer la depreciation on aura besoin de l'usage, l'age et la source d'energie
// lage du vehicule est en mois
