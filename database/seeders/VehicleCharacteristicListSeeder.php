<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Dealer;
use App\Models\Price;
use App\Models\Status;
use App\Models\Usage;
use App\Models\VehicleEnergy;
use App\Models\VehicleGenre;
use App\Models\VehicleGenreUsage;
use App\Models\VehicleModel;
use App\Models\VehicleCharacteristic;
use App\Enums\StatusEnum;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class VehicleCharacteristicListSeeder extends Seeder
{
    /** GenreVehicule (normalized) => [vehicle_genre code, usage code] */
    private const GENRE_USAGE_MAP = [
        '4x4' => ['VP', 'PRIV'],
        'suv' => ['VP', 'PRIV'],
        'voiture particuliere' => ['VP', 'PRIV'],
        'bus' => ['TCP', 'TURB'],
        'minibus' => ['TCP', 'TURB'],
        'transport prive voyageur' => ['TCP', 'TURB'],
        'camion 2,5 t a 5 t' => ['CAM', 'CAM1'],
        'camion plus de 5 t' => ['CAM', 'CAM2'],
        'petit utilitaire' => ['CTTE', 'PRIV'],
        'utilitaire' => ['CTTE', 'PRIV'],
        'camionnette' => ['CTTE', 'TRMA'],
        'cyclo moto' => ['MT', 'PRIV'],
        'semi remorque' => ['REM', 'TMEL'],
        'taxi compteur' => ['VP', 'TAXI'],
        'tracteur routier' => ['TRR', 'TLGD'],
    ];

    private $statusId;
    private $userId = 1;
    /** @var array<string, int> genre_usage_key => id */
    private $genreUsageIdsByKey;
    private $brandsByCode;
    private $vehicleModelsByBrandAndLabel;
    private $dealersByName;
    private $energiesByLabel;

    /**
     * Run the database seeds.
     * Seeds vehicle characteristics from data/conseilauto_data_20260225_230502.json
     * using the same logic as VehicleCharacteristicController::store().
     * Maps: vehicle_model_id←NomCommercial+Marque, vehicle_energy_id←Energie, dealer_id←concessionnaire,
     * type←types, equipments←Equipement, fiscal_power←PuissanceFiscale, nb_seats←Nbreplace,
     * new_market_value←HT_HD, date←dateParution (today if invalid). vehicle_genre_usage_id from GenreVehicule per prompt.
     *
     * @return void
     */
    public function run(): void
    {
        $path = base_path('data/conseilauto_data_20260225_230502.json');

        if (!file_exists($path)) {
            $this->command->warn("File not found: {$path}");
            return;
        }

        $this->command->info('Loading JSON...');
        $data = json_decode(file_get_contents($path), true);

        if (!is_array($data)) {
            $this->command->warn('Invalid JSON.');
            return;
        }

        $this->statusId = Status::firstWhere('code', StatusEnum::ACTIVE)?->id;
        $this->loadLookups();

        if (empty($this->genreUsageIdsByKey)) {
            $this->command->warn('No VehicleGenreUsage found for genre/usage mapping. Run VehicleGenreSeeder, UsageSeeder, VehicleGenreUsageSeeder.');
            return;
        }

        $created = 0;
        $skipped = 0;

        foreach ($data as $row) {
            $vehicleGenreUsageId = $this->resolveVehicleGenreUsageId($row);
            if (!$vehicleGenreUsageId) {
                continue;
            }

            $vehicleModelId = $this->resolveVehicleModelId($row);
            $vehicleEnergyId = $this->resolveVehicleEnergyId($row);
            $dealerId = $this->resolveDealerId($row);

            if (!$vehicleModelId || !$vehicleEnergyId || !$dealerId) {
                $skipped++;
                continue;
            }

            $type = isset($row['types']) ? trim((string) $row['types']) : null;
            $equipments = isset($row['Equipement']) ? trim((string) $row['Equipement']) : null;
            $fiscalPower = isset($row['PuissanceFiscale']) ? (int) $row['PuissanceFiscale'] : null;
            $nbSeats = isset($row['Nbreplace']) ? (int) $row['Nbreplace'] : null;
            $newMarketValue = isset($row['TTC']) ? (float) $row['TTC'] : null;
            $dateParution = isset($row['dateParution']) ? trim((string) $row['dateParution']) : null;
            $date = $this->parseDate($dateParution);

            $vehicleCharacteristic = VehicleCharacteristic::create([
                'vehicle_model_id' => $vehicleModelId,
                'vehicle_genre_usage_id' => $vehicleGenreUsageId,
                'vehicle_energy_id' => $vehicleEnergyId,
                'dealer_id' => $dealerId,
                'type' => $type,
                'equipments' => $equipments,
                'options' => null,
                'fiscal_power' => $fiscalPower,
                'nb_seats' => $nbSeats,
                'new_market_value' => $newMarketValue,
                'status_id' => $this->statusId,
                'created_by' => $this->userId,
                'updated_by' => $this->userId,
            ]);

            if ($vehicleCharacteristic && $newMarketValue !== null) {
                Price::create([
                    'value' => $newMarketValue,
                    'date' => $date,
                    'vehicle_characteristic_id' => $vehicleCharacteristic->id,
                    'status_id' => $this->statusId,
                    'created_by' => $this->userId,
                    'updated_by' => $this->userId,
                ]);
            }

            $created++;
        }

        $this->command->info("Vehicle characteristics: {$created} created, {$skipped} skipped (missing refs).");
    }

    private function loadLookups(): void
    {
        $this->genreUsageIdsByKey = [];
        $genreCodes = array_unique(array_column(self::GENRE_USAGE_MAP, 0));
        $usageCodes = array_unique(array_column(self::GENRE_USAGE_MAP, 1));
        $genreIds = VehicleGenre::whereIn('code', $genreCodes)->pluck('id', 'code')->all();
        $usageIds = Usage::whereIn('code', $usageCodes)->pluck('id', 'code')->all();
        foreach (self::GENRE_USAGE_MAP as $pair) {
            [$gCode, $uCode] = $pair;
            $key = $gCode . '|' . $uCode;
            if (isset($genreIds[$gCode], $usageIds[$uCode])) {
                $vgu = VehicleGenreUsage::where('vehicle_genre_id', $genreIds[$gCode])
                    ->where('usage_id', $usageIds[$uCode])
                    ->first();
                if ($vgu) {
                    $this->genreUsageIdsByKey[$key] = $vgu->id;
                }
            }
        }

        $this->brandsByCode = Brand::all()->keyBy('code');
        $this->dealersByName = Dealer::all()->keyBy(fn (Dealer $d) => Str::upper(trim((string) $d->name)));
        $this->energiesByLabel = VehicleEnergy::all()->keyBy(fn (VehicleEnergy $e) => Str::upper(trim((string) $e->label)));

        if (!$this->energiesByLabel->has('DIESEL')) {
            $gasoil = VehicleEnergy::where('label', 'GASOIL')->first();
            if ($gasoil) {
                $this->energiesByLabel->put('DIESEL', $gasoil);
            }
        }

        $this->vehicleModelsByBrandAndLabel = VehicleModel::with('brand')
            ->get()
            ->mapWithKeys(fn (VehicleModel $m) => [($m->brand?->id ?? 0) . '|' . trim((string) $m->label) => $m]);
    }

    private function resolveVehicleGenreUsageId(array $row): ?int
    {
        $raw = isset($row['GenreVehicule']) ? (string) $row['GenreVehicule'] : '';
        $normalized = Str::lower(trim(str_replace(["\r", "\n"], '', $raw)));
        if ($normalized === '') {
            return null;
        }
        $pair = self::GENRE_USAGE_MAP[$normalized] ?? null;
        if ($pair === null) {
            return null;
        }
        $key = $pair[0] . '|' . $pair[1];
        return $this->genreUsageIdsByKey[$key] ?? null;
    }

    private function resolveVehicleModelId(array $row): ?int
    {
        $marque = isset($row['Marque']) ? trim((string) $row['Marque']) : '';
        $nomCommercial = isset($row['NomCommercial']) ? trim((string) $row['NomCommercial']) : '';
        if ($marque === '' || $nomCommercial === '') {
            return null;
        }

        $brand = $this->brandsByCode->get(Str::slug($marque))
            ?? Brand::whereRaw('UPPER(TRIM(label)) = ?', [Str::upper($marque)])->first();
        if (!$brand) {
            return null;
        }

        $key = $brand->id . '|' . $nomCommercial;
        $model = $this->vehicleModelsByBrandAndLabel->get($key);
        return $model?->id;
    }

    private function resolveVehicleEnergyId(array $row): ?int
    {
        $energie = isset($row['Energie']) ? Str::upper(trim((string) $row['Energie'])) : '';
        if ($energie === '') {
            return null;
        }
        $energy = $this->energiesByLabel->get($energie);
        return $energy?->id;
    }

    private function resolveDealerId(array $row): ?int
    {
        $name = isset($row['concessionnaire']) ? trim((string) $row['concessionnaire']) : '';
        if ($name === '') {
            return null;
        }
        $dealer = $this->dealersByName->get(Str::upper($name));
        return $dealer?->id;
    }

    /**
     * Parse date from JSON; return today (Y-m-d) if null, empty or invalid.
     */
    private function parseDate(?string $value): string
    {
        if ($value === null || $value === '') {
            return now()->toDateString();
        }
        try {
            $parsed = Carbon::parse($value);
            $date = $parsed->format('Y-m-d');
            if ($date === '0000-00-00' || $parsed->year < 1900) {
                return now()->toDateString();
            }
            return $date;
        } catch (\Throwable) {
            return now()->toDateString();
        }
    }
}
