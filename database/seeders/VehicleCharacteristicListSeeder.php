<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Dealer;
use App\Models\Price;
use App\Models\Status;
use App\Models\VehicleEnergy;
use App\Models\VehicleGenreUsage;
use App\Models\VehicleModel;
use App\Models\VehicleCharacteristic;
use App\Enums\StatusEnum;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class VehicleCharacteristicListSeeder extends Seeder
{
    private const VEHICLE_GENRE_CODES = ['VP', 'CTTE', 'CAM', 'TCP', 'MT', 'REM', 'TRR', 'ENG', 'VASP', 'CITE'];

    /** GenreVehicule / GenreVehicule_Predit (normalized) => [vehicle_genre code, default usage code] */
    private const GENRE_USAGE_MAP = [
        '4x4' => ['VP', 'PRIV'],
        'suv' => ['VP', 'PRIV'],
        'voiture particuliere' => ['VP', 'PRIV'],
        'bus' => ['TCP', 'TURB'],
        'minibus' => ['TCP', 'TURB'],
        'transport prive voyageur' => ['TCP', 'TURB'],
        'camion 2,5 t a 5 t' => ['CAM', 'CAM1'],
        'camion 2.5t a 5t' => ['CAM', 'CAM1'],
        'camion plus de 5 t' => ['CAM', 'CAM2'],
        'camion plus de 5t' => ['CAM', 'CAM2'],
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
    /** @var array<string, int> genre_code|usage_code => vehicle_genre_usage id */
    private $genreUsageIdsByKey = [];
    /** @var list<string> */
    private $genreCodes = [];
    private $brandsByCode;
    private $vehicleModelsByBrandAndLabel;
    private $dealersByName;
    private $energiesByLabel;

    /**
     * Seeds vehicle characteristics from data/donnees_vehicule_20260609_223615_filled.json.
     * Maps: vehicle_model_id←NomCommercial+Marque, vehicle_energy_id←Energie, dealer_id←concessionnaire,
     * type←types, equipments←Equipement, fiscal_power←PuissanceFiscale, nb_seats←Nbreplace,
     * new_market_value←TTC, date←dateParution.
     * Associations genre/usage via GenreVehicule (ou GenreVehicule_Predit) + Usage (codes séparés par /).
     */
    public function run(): void
    {
        $path = base_path('data/donnees_vehicule_20260609_223615_filled.json');

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
            $this->command->warn('No VehicleGenreUsage found. Run VehicleGenreSeeder, UsageSeeder, VehicleGenreUsageSeeder.');
            return;
        }

        $created = 0;
        $skipped = 0;
        $skippedGenreUsage = 0;

        foreach ($data as $row) {
            $vehicleGenreUsageIds = $this->resolveVehicleGenreUsageIds($row);
            if (empty($vehicleGenreUsageIds)) {
                $skippedGenreUsage++;
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

            foreach ($vehicleGenreUsageIds as $vehicleGenreUsageId) {
                $vehicleCharacteristic->vehicleCharacteristicGenreUsages()->updateOrCreate(
                    ['vehicle_genre_usage_id' => $vehicleGenreUsageId],
                    [
                        'status_id' => $this->statusId,
                        'created_by' => $this->userId,
                        'updated_by' => $this->userId,
                    ]
                );
            }

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

        $this->command->info("Vehicle characteristics: {$created} created, {$skipped} skipped (missing refs), {$skippedGenreUsage} skipped (genre/usage).");
    }

    private function loadLookups(): void
    {
        $this->genreUsageIdsByKey = [];
        $this->genreCodes = [];

        $vehicleGenreUsages = VehicleGenreUsage::with('vehicleGenre:id,code', 'usage:id,code')->get();

        foreach ($vehicleGenreUsages as $vehicleGenreUsage) {
            $genreCode = $vehicleGenreUsage->vehicleGenre?->code;
            $usageCode = $vehicleGenreUsage->usage?->code;

            if (!$genreCode || !$usageCode) {
                continue;
            }

            $key = $genreCode . '|' . $usageCode;
            $this->genreUsageIdsByKey[$key] = $vehicleGenreUsage->id;

            if (!in_array($genreCode, $this->genreCodes, true)) {
                $this->genreCodes[] = $genreCode;
            }
        }

        $preferredOrder = self::VEHICLE_GENRE_CODES;
        usort($this->genreCodes, function (string $a, string $b) use ($preferredOrder) {
            $posA = array_search($a, $preferredOrder, true);
            $posB = array_search($b, $preferredOrder, true);

            return ($posA === false ? 999 : $posA) <=> ($posB === false ? 999 : $posB);
        });

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

    /**
     * @return list<int>
     */
    private function resolveVehicleGenreUsageIds(array $row): array
    {
        $genreCode = $this->resolveGenreCode($row);
        $usageCodes = $this->resolveUsageCodes($row, $genreCode);

        if (empty($usageCodes)) {
            return [];
        }

        if ($genreCode !== null) {
            $ids = $this->lookupGenreUsageIds($genreCode, $usageCodes);
            if (!empty($ids)) {
                return $ids;
            }
        }

        foreach ($this->genreCodes as $candidateGenreCode) {
            $ids = $this->lookupGenreUsageIds($candidateGenreCode, $usageCodes);
            if (count($ids) === count($usageCodes)) {
                return $ids;
            }
        }

        return $this->lookupPartialGenreUsageIds($usageCodes);
    }

    /**
     * @param list<string> $usageCodes
     * @return list<int>
     */
    private function lookupGenreUsageIds(string $genreCode, array $usageCodes): array
    {
        $ids = [];

        foreach ($usageCodes as $usageCode) {
            $key = $genreCode . '|' . $usageCode;
            if (!isset($this->genreUsageIdsByKey[$key])) {
                return [];
            }
            $ids[] = $this->genreUsageIdsByKey[$key];
        }

        return array_values(array_unique($ids));
    }

    /**
     * @param list<string> $usageCodes
     * @return list<int>
     */
    private function lookupPartialGenreUsageIds(array $usageCodes): array
    {
        $ids = [];

        foreach ($usageCodes as $usageCode) {
            foreach ($this->genreCodes as $genreCode) {
                $key = $genreCode . '|' . $usageCode;
                if (isset($this->genreUsageIdsByKey[$key])) {
                    $ids[] = $this->genreUsageIdsByKey[$key];
                    break;
                }
            }
        }

        return array_values(array_unique($ids));
    }

    private function resolveGenreCode(array $row): ?string
    {
        $normalized = $this->normalizeGenreLabel($row['GenreVehicule'] ?? null);

        if ($normalized === '') {
            $normalized = $this->normalizeGenreLabel($row['GenreVehicule_Predit'] ?? null);
        }

        if ($normalized === '') {
            return $this->extractGenreCodeFromUsage($row['Usage'] ?? null);
        }

        $pair = self::GENRE_USAGE_MAP[$normalized] ?? null;

        return $pair[0] ?? null;
    }

    /**
     * @return list<string>
     */
    private function resolveUsageCodes(array $row, ?string $genreCode): array
    {
        $usageRaw = isset($row['Usage']) ? trim((string) $row['Usage']) : '';

        if ($usageRaw === '') {
            $normalized = $this->normalizeGenreLabel($row['GenreVehicule'] ?? $row['GenreVehicule_Predit'] ?? null);
            $pair = self::GENRE_USAGE_MAP[$normalized] ?? null;

            return $pair ? [$pair[1]] : [];
        }

        $parts = array_values(array_filter(array_map('trim', explode('/', $usageRaw))));
        $usageCodes = [];

        foreach ($parts as $part) {
            $upper = Str::upper($part);

            if (in_array($upper, self::VEHICLE_GENRE_CODES, true)) {
                continue;
            }

            $usageCodes[] = $upper;
        }

        return array_values(array_unique($usageCodes));
    }

    private function extractGenreCodeFromUsage(?string $usageRaw): ?string
    {
        if ($usageRaw === null || trim($usageRaw) === '') {
            return null;
        }

        foreach (array_map('trim', explode('/', $usageRaw)) as $part) {
            $upper = Str::upper($part);
            if (in_array($upper, self::VEHICLE_GENRE_CODES, true)) {
                return $upper;
            }
        }

        return null;
    }

    private function normalizeGenreLabel(?string $value): string
    {
        if ($value === null) {
            return '';
        }

        $normalized = Str::lower(trim(str_replace(["\r", "\n"], '', $value)));
        $normalized = str_replace('camion plus de 5t', 'camion plus de 5 t', $normalized);
        $normalized = str_replace('camion 2.5t a 5t', 'camion 2,5 t a 5 t', $normalized);

        return $normalized;
    }

    private function resolveVehicleModelId(array $row): ?int
    {
        $marque = isset($row['Marque']) ? trim((string) $row['Marque']) : '';
        $nomCommercial = isset($row['NomCommercial']) ? trim((string) $row['NomCommercial']) : '';
        $modele = isset($row['Modele']) ? trim((string) $row['Modele']) : '';

        if ($marque === '' || $nomCommercial === '') {
            return null;
        }

        $slug = Str::slug($marque);
        $brand = $this->brandsByCode[$slug] ?? null;

        if (!$brand) {
            $brand = Brand::whereRaw('UPPER(TRIM(label)) = ?', [Str::upper($marque)])->first();

            if (!$brand) {
                $normalized = preg_replace('/\s+/', '', Str::upper($marque));
                $brand = Brand::whereRaw('REPLACE(UPPER(label), " ", "") = ?', [$normalized])->first();
            }

            if (!$brand) {
                $code = $slug !== '' ? $slug : 'brand-' . Str::random(6);
                $brand = Brand::create([
                    'code' => $code,
                    'label' => $marque,
                    'description' => $marque,
                    'status_id' => $this->statusId,
                    'created_by' => $this->userId,
                    'updated_by' => $this->userId,
                ]);
            }

            $this->brandsByCode[$brand->code] = $brand;
        }

        $key = $brand->id . '|' . $nomCommercial;
        $model = $this->vehicleModelsByBrandAndLabel[$key] ?? null;

        if (!$model) {
            $codeBase = Str::slug($brand->code . '-' . $nomCommercial);
            $code = $codeBase !== '' ? $codeBase : 'model-' . Str::random(6);
            $suffix = 1;

            while (VehicleModel::where('code', $code)->exists()) {
                $code = $codeBase . '-' . $suffix++;
            }

            $model = VehicleModel::create([
                'code' => $code,
                'label' => $nomCommercial,
                'description' => $modele !== '' ? $modele : $nomCommercial,
                'brand_id' => $brand->id,
                'status_id' => $this->statusId,
                'created_by' => $this->userId,
                'updated_by' => $this->userId,
            ]);

            $this->vehicleModelsByBrandAndLabel[$key] = $model;
        }

        return $model->id;
    }

    private function resolveVehicleEnergyId(array $row): ?int
    {
        $energie = isset($row['Energie']) ? Str::upper(trim((string) $row['Energie'])) : '';
        if ($energie === '') {
            return null;
        }

        $energy = $this->energiesByLabel->get($energie);

        if (!$energy) {
            $codeBase = 'VE-' . Str::slug($energie);
            $code = $codeBase !== '' ? $codeBase : 'VE-' . Str::random(4);
            $suffix = 1;

            while (VehicleEnergy::where('code', $code)->exists()) {
                $code = $codeBase . '-' . $suffix++;
            }

            $energy = VehicleEnergy::create([
                'code' => $code,
                'label' => $energie,
                'description' => $energie,
                'status_id' => $this->statusId,
            ]);

            $this->energiesByLabel[$energie] = $energy;
        }

        return $energy->id;
    }

    private function resolveDealerId(array $row): ?int
    {
        $name = isset($row['concessionnaire']) ? trim((string) $row['concessionnaire']) : '';
        if ($name === '') {
            return null;
        }

        $upper = Str::upper($name);
        $dealer = $this->dealersByName[$upper] ?? null;

        if (!$dealer) {
            $dealer = Dealer::create([
                'name' => $name,
                'email' => null,
                'address' => null,
                'telephone' => null,
                'status_id' => $this->statusId,
                'created_by' => $this->userId,
                'updated_by' => $this->userId,
            ]);

            $this->dealersByName[$upper] = $dealer;
        }

        return $dealer->id;
    }

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
