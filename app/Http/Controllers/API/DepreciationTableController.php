<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use App\Models\Price;
use App\Models\Usage;
use App\Models\Status;
use App\Enums\StatusEnum;
use App\Models\VehicleAge;
use App\Models\Calculation;
use App\Models\Transaction;
use App\Models\VehicleGenre;
use Illuminate\Http\Request;
use App\Models\VehicleEnergy;
use App\Models\DepreciationTable;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Essa\APIToolKit\Api\ApiResponse;
use App\Models\VehicleCharacteristic;
use App\Jobs\GenerateEvaluationReportPdfJob;
use App\Services\MarketValue\MarketValueService;
use App\Http\Resources\Calculation\CalculationResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Http\Requests\DepreciationTable\CreateMarketValueRequest;
use App\Http\Resources\DepreciationTable\DepreciationTableResource;
use App\Http\Requests\DepreciationTable\CreateDepreciationTableRequest;
use App\Http\Requests\DepreciationTable\UpdateDepreciationTableRequest;
use App\Http\Requests\DepreciationTable\CreateTheoricalMarketValueRequest;

/**
 * @group Gestion des tableaux de dépréciation
 *
 * APIs pour la gestion des tableaux de dépréciation
 */
class DepreciationTableController extends Controller
{
    use ApiResponse;

    public function __construct()
    {

    }

    /**
     * Lister tous les tableaux de dépréciation
     *
     * @authenticated
     */
    public function index(): AnonymousResourceCollection
    {
        $depreciationTables = DepreciationTable::with('vehicleGenre', 'vehicleAge', 'status');
        
        if(request()->has('vehicle_genre_id')){
            $depreciationTables = $depreciationTables->where('vehicle_genre_id', VehicleGenre::keyFromHashId(request()->vehicle_genre_id));
        }

        $depreciationTables = $depreciationTables->latest('created_at')->useFilters()->dynamicPaginate();

        return DepreciationTableResource::collection($depreciationTables);
    }

    /**
     * Calculer la valeur vénale théorique d'un véhicule
     *
     * @authenticated
     */
    public function calculate_theoretical_market_value(CreateTheoricalMarketValueRequest $request): JsonResponse
    {
        $credit = Transaction::where('entity_id', auth()->user()->entity_id)->where('status_id', Status::where('code', StatusEnum::PERFORMED)->first()->id)->sum('quantity') - Calculation::where('entity_id', auth()->user()->entity_id)->where('status_id', Status::where('code', StatusEnum::SUCCESS)->first()->id)->count();

        if($credit < 0){
            return $this->responseUnprocessable('Vous n\'avez pas assez de crédit pour effectuer cette action');
        }

        $vehicleCharacteristic = VehicleCharacteristic::with('vehicleEnergy', 'vehicleGenreUsage', 'vehicleGenreUsage.vehicleGenre', 'vehicleGenreUsage.usage')->findOrFail($request->vehicle_characteristic_id);
        $price = Price::find($request->price_id);
        if(!$price){
            return $this->responseUnprocessable('Le prix de la caractéristique du véhicule est requis');
        }
        $vehicle_new_value = $price?->value ?? 0;
        $marketValueService = app(MarketValueService::class);
        $result = $marketValueService->calculateTheoreticalMarketValue($vehicleCharacteristic->vehicleGenreUsage->id, $vehicleCharacteristic->vehicle_energy_id, $vehicle_new_value, $request->vehicle_mileage, $request->first_entry_into_circulation_date, $request->expertise_date);
        $result = (object) $result;

        $kilometric_incidence = 0;
        $is_up = null;
        if ($result->vehicle_energy->code == 'VE01') {
            $max_mileage_essence_per_month = $result->vehicle_genre_usage->max_mileage_essence_per_year ? $result->vehicle_genre_usage->max_mileage_essence_per_year / 12 : $result->vehicle_genre_usage->vehicleGenre->max_mileage_essence_per_year / 12;
            $kilometric_incidence = (($max_mileage_essence_per_month * $result->month_diff) - $request->vehicle_mileage) * 25;
        } else {
            $max_mileage_diesel_per_month = $result->vehicle_genre_usage->max_mileage_diesel_per_year ? $result->vehicle_genre_usage->max_mileage_diesel_per_year / 12 : $result->vehicle_genre_usage->vehicleGenre->max_mileage_diesel_per_year / 12;
            $kilometric_incidence = (($max_mileage_diesel_per_month * $result->month_diff) - $request->vehicle_mileage) * 40;
        }

        if ($kilometric_incidence > 0) {
            $is_up = true;
        } else {
            $is_up = false;
            $kilometric_incidence = -1 * $kilometric_incidence;
        }

        $market_incidence_rate = $request->market_incidence_rate ?? 0;

        $market_incidence = ceil($result->vehicle_new_value * $market_incidence_rate / 100);

        if($kilometric_incidence > $result->theorical_vehicle_market_value){
            $kilometric_incidence = $result->theorical_vehicle_market_value / 2;
        }
        $vehicle_market_value = $is_up ? $result->theorical_vehicle_market_value + $market_incidence + $kilometric_incidence : $result->theorical_vehicle_market_value + $market_incidence - $kilometric_incidence;
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
            'vehicle_market_value' =>  ceil($result->vehicle_new_value - ($result->vehicle_new_value * $depreciation_rate / 100))
        ];

        $calculation = Calculation::with('entity', 'vehicleCharacteristic', 'vehicleCharacteristic.vehicleEnergy', 'vehicleCharacteristic.vehicleGenreUsage', 'vehicleCharacteristic.vehicleGenreUsage.vehicleGenre', 'vehicleCharacteristic.vehicleGenreUsage.usage')->create([
            'reference' => 'EV-'.date('YmdHis'),
            'license_plate' => $request->license_plate,
            'mileage' => $request->vehicle_mileage,
            'serial_number' => $request->serial_number,
            'first_entry_into_circulation_date' => $request->first_entry_into_circulation_date,
            'calculation_date' => $request->expertise_date,
            'insured' => $request->insured,
            'evaluation' => json_encode($result),
            'vehicle_characteristic_id' => $vehicleCharacteristic->id,
            'entity_id' => auth()->user()->entity_id,
            'status_id' => Status::where('code', StatusEnum::SUCCESS)->first()->id,
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
        ]);

        $calculation = Calculation::with('entity', 'vehicleCharacteristic', 'vehicleCharacteristic.vehicleEnergy', 'vehicleCharacteristic.vehicleGenreUsage', 'vehicleCharacteristic.vehicleGenreUsage.vehicleGenre', 'vehicleCharacteristic.vehicleGenreUsage.usage')->where('id', $calculation->id)->first();

        dispatch(new GenerateEvaluationReportPdfJob($calculation));

        return $this->responseSuccess('DepreciationTable created successfully', [
            'calculation' => new CalculationResource($calculation),
            'credit' => $credit - 1,
            'pdf' => url('storage/evaluation_report/'.$calculation->reference.'.pdf?v='.time()),
        ]);
    }

    // /**
    //  * Calculer la valeur vénale d'un véhicule
    //  *
    //  * @authenticated
    //  */
    // public function calculate_market_value(CreateMarketValueRequest $request): JsonResponse
    // {
    //     $year_diff = ceil(Carbon::parse($request->first_entry_into_circulation_date)->diffInYears($request->expertise_date));
    //     $month_diff = ceil(Carbon::parse($request->first_entry_into_circulation_date)->diffInMonths($request->expertise_date));
    //     $usage = VehicleGenre::findOrFail($request->usage_id);
    //     $vehicle_energy = VehicleEnergy::findOrFail($request->vehicle_energy_id);
    //     $vehicle_age = VehicleAge::firstWhere('value', $month_diff);
    //     $depreciation_table = DepreciationTable::where('usage_id', $request->usage_id)->where('vehicle_age_id', $vehicle_age->id)->firstOrFail();

    //     $kilometric_incidence = 0;
    //     $is_up = null;
    //     if ($vehicle_energy->code == 'VE01') {
    //         $kilometric_incidence = ($request->vehicle_mileage - ($usage->max_mileage_essence_per_year * $year_diff)) * 25;
    //     } else {
    //         $kilometric_incidence = ($request->vehicle_mileage - ($usage->max_mileage_diesel_per_year * $year_diff)) * 40;
    //     }

    //     if ($kilometric_incidence > 0) {
    //         $is_up = true;
    //     } else {
    //         $is_up = false;
    //     }

    //     $expertise_date = $request->expertise_date;
    //     $first_entry_into_circulation_date = $request->first_entry_into_circulation_date;
    //     $vehicle_new_value = $request->vehicle_new_value;
    //     $vehicle_age = $vehicle_age->value;
    //     $theorical_depreciation_rate = $depreciation_table->value;
    //     $theorical_vehicle_market_value = $vehicle_new_value - ($vehicle_new_value * $theorical_depreciation_rate / 100);

    //     $moins_value_work = 0;
    //     $market_incidence = $vehicle_new_value * $request->market_incidence_rate / 100;

    //     $vehicle_market_value = $theorical_vehicle_market_value + $market_incidence - $moins_value_work - $kilometric_incidence;




    //     return $this->responseSuccess('DepreciationTable created successfully', [
    //         'expertise_date' => $expertise_date,
    //         'first_entry_into_circulation_date' => $first_entry_into_circulation_date,
    //         'vehicle_new_value' => $vehicle_new_value,
    //         'vehicle_age' => $vehicle_age,
    //         'theorical_depreciation_rate' => $theorical_depreciation_rate,
    //         'theorical_vehicle_market_value' => $theorical_vehicle_market_value,
    //         'moins_value_work' => $moins_value_work,
    //         'is_up' => $is_up,
    //         'kilometric_incidence' => $kilometric_incidence,
    //         'market_incidence' => $market_incidence,
    //         'vehicle_market_value' => $vehicle_market_value,
    //     ]);
    // }

    /**
     * Créer un nouveau tableau de dépréciation
     *
     * @authenticated
     */
    public function store(CreateDepreciationTableRequest $request): JsonResponse
    {
        $depreciationTable = DepreciationTable::create([
            'value' => $request->value,
            'vehicle_genre_id' => $request->vehicle_genre_id,
            'vehicle_age_id' => $request->vehicle_age_id,
            'status_id' => Status::where('code', StatusEnum::ACTIVE)->first()->id,
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
        ]);

        return $this->responseCreated('DepreciationTable created successfully', new DepreciationTableResource($depreciationTable));
    }

    /**
     * Afficher un tableau de dépréciation
     *
     * @authenticated
     */
    public function show($id): JsonResponse
    {
        $depreciationTable = DepreciationTable::findOrFail(DepreciationTable::keyFromHashId($id));
        return $this->responseSuccess(null, new DepreciationTableResource($depreciationTable));
    }

    /**
     * Mettre à jour un tableau de dépréciation
     *
     * @authenticated
     */
    public function update(UpdateDepreciationTableRequest $request, $id): JsonResponse
    {
        $depreciationTable = DepreciationTable::findOrFail(DepreciationTable::keyFromHashId($id));
        $depreciationTable->update([
            'value' => $request->value,
            'vehicle_genre_id' => $request->vehicle_genre_id,
            'vehicle_age_id' => $request->vehicle_age_id,
            'status_id' => Status::where('code', StatusEnum::ACTIVE)->first()->id,
            'updated_by' => auth()->user()->id,
        ]);

        return $this->responseSuccess('DepreciationTable updated Successfully', new DepreciationTableResource($depreciationTable));
    }

    /**
     * Supprimer un tableau de dépréciation
     *
     * @authenticated
     */
    public function destroy($id): JsonResponse
    {
        $depreciationTable = DepreciationTable::findOrFail(DepreciationTable::keyFromHashId($id));
        // $depreciationTable->delete();

        return $this->responseDeleted();
    }

   
}
