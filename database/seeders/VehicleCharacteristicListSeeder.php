<?php

namespace Database\Seeders;

use App\Enums\StatusEnum;
use App\Models\Brand;
use App\Models\Dealer;
use App\Models\Price;
use App\Models\Status;
use App\Models\Usage;
use App\Models\VehicleCharacteristic;
use App\Models\VehicleEnergy;
use App\Models\VehicleGenre;
use App\Models\VehicleGenreUsage;
use App\Models\VehicleModel;
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

    /**
     * Normalized Usage token (label fragment / alias) => usage code.
     * Covers JSON values that do not exactly equal Usage.label or Usage.code.
     */
    private const USAGE_ALIASES = [
        'prive' => 'PRIV',
        'prive personnel' => 'PRIV',
        'personnel' => 'PRIV',
        'location' => 'LOUT',
        'vp' => 'PRIV',
    ];

    private $statusId;
    private $userId = 1;
    /** @var array<string, int> genre_code|usage_code => vehicle_genre_usage id */
    private $genreUsageIdsByKey = [];
    /** @var list<string> */
    private $genreCodes = [];
    /** @var array<string, VehicleGenre> */
    private $vehicleGenresByCode = [];
    /** @var array<string, Usage> UPPER(code) => Usage */
    private $usagesByCode = [];
    /** @var array<string, Usage> normalized label => Usage */
    private $usagesByNormalizedLabel = [];
    /** @var list<string> */
    private $knownUsageLabelsNormalized = [];
    /** @var list<string> tokens created as Usage during seeding */
    private $createdUsageCodes = [];
    /** @var list<string> genre|usage keys created during seeding */
    private $createdGenreUsageKeys = [];
    /** @var array<string, int> unmatched raw tokens => count */
    private $unmatchedUsageTokens = [];
    private $brandsByCode;
    private $vehicleModelsByBrandAndLabel;
    private $dealersByName;
    private $energiesByLabel;

    /**
     * Seeds vehicle characteristics from data/donnees_vehicule_20260721_filled.json.
     * Maps: vehicle_model_id←NomCommercial+Marque, vehicle_energy_id←Energie, dealer_id←concessionnaire,
     * type←types, equipments←Equipement, fiscal_power←PuissanceFiscale, nb_seats←Nbreplace,
     * new_market_value←TTC, date←dateParution.
     * Associations genre/usage via GenreVehicule (sinon GenreVehicule_Predit) + Usage (code ou label, séparés par /).
     */
    public function run(): void
    {
        $path = base_path('data/donnees_vehicule_20260721_filled.json');

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

        if (empty($this->vehicleGenresByCode)) {
            $this->command->warn('No VehicleGenre found. Run VehicleGenreSeeder.');
            return;
        }

        $created = 0;
        $skipped = 0;
        $skippedGenreUsage = 0;
        $genreUsageLinks = 0;

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
                'date' => $date,
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
                $genreUsageLinks++;
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
        $this->command->info("VehicleCharacteristicGenreUsage links: {$genreUsageLinks}.");

        if (!empty($this->createdUsageCodes)) {
            $this->command->info('Usages created: ' . implode(', ', array_unique($this->createdUsageCodes)));
        }

        if (!empty($this->createdGenreUsageKeys)) {
            $this->command->info('VehicleGenreUsage created: ' . implode(', ', array_unique($this->createdGenreUsageKeys)));
        }

        if (!empty($this->unmatchedUsageTokens)) {
            $this->command->warn('Usage tokens still unmatched (should be rare):');
            foreach ($this->unmatchedUsageTokens as $token => $count) {
                $this->command->warn("  - {$token} ({$count})");
            }
        }
    }

    private function loadLookups(): void
    {
        $this->genreUsageIdsByKey = [];
        $this->genreCodes = [];
        $this->vehicleGenresByCode = [];
        $this->usagesByCode = [];
        $this->usagesByNormalizedLabel = [];
        $this->knownUsageLabelsNormalized = [];

        foreach (VehicleGenre::all() as $genre) {
            $code = Str::upper(trim((string) $genre->code));
            if ($code === '') {
                continue;
            }
            $this->vehicleGenresByCode[$code] = $genre;
            $this->genreCodes[] = $code;
        }

        foreach (Usage::all() as $usage) {
            $this->indexUsage($usage);
        }

        $vehicleGenreUsages = VehicleGenreUsage::with('vehicleGenre:id,code', 'usage:id,code')->get();

        foreach ($vehicleGenreUsages as $vehicleGenreUsage) {
            $genreCode = $vehicleGenreUsage->vehicleGenre?->code;
            $usageCode = $vehicleGenreUsage->usage?->code;

            if (!$genreCode || !$usageCode) {
                continue;
            }

            $key = Str::upper($genreCode) . '|' . Str::upper($usageCode);
            $this->genreUsageIdsByKey[$key] = $vehicleGenreUsage->id;
        }

        $preferredOrder = self::VEHICLE_GENRE_CODES;
        usort($this->genreCodes, function (string $a, string $b) use ($preferredOrder) {
            $posA = array_search($a, $preferredOrder, true);
            $posB = array_search($b, $preferredOrder, true);

            return ($posA === false ? 999 : $posA) <=> ($posB === false ? 999 : $posB);
        });

        usort($this->knownUsageLabelsNormalized, fn (string $a, string $b) => strlen($b) <=> strlen($a));

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

    private function indexUsage(Usage $usage): void
    {
        $code = Str::upper(trim((string) $usage->code));
        if ($code !== '') {
            $this->usagesByCode[$code] = $usage;
        }

        $normalizedLabel = $this->normalizeLookupKey($usage->label);
        if ($normalizedLabel !== '') {
            $this->usagesByNormalizedLabel[$normalizedLabel] = $usage;
            $collapsed = preg_replace('/\s*\/\s*/', '/', $normalizedLabel) ?? $normalizedLabel;
            $this->usagesByNormalizedLabel[$collapsed] = $usage;
            if (!in_array($normalizedLabel, $this->knownUsageLabelsNormalized, true)) {
                $this->knownUsageLabelsNormalized[] = $normalizedLabel;
            }
        }
    }

    /**
     * @return list<int>
     */
    private function resolveVehicleGenreUsageIds(array $row): array
    {
        $genreCode = $this->resolveGenreCode($row);
        $usageCodes = $this->resolveUsageCodes($row, $genreCode);

        if ($genreCode === null || empty($usageCodes)) {
            return [];
        }

        $ids = [];

        foreach ($usageCodes as $usageCode) {
            $id = $this->ensureGenreUsageId($genreCode, $usageCode);
            if ($id !== null) {
                $ids[] = $id;
            }
        }

        return array_values(array_unique($ids));
    }

    private function ensureGenreUsageId(string $genreCode, string $usageCode): ?int
    {
        $genreCode = Str::upper($genreCode);
        $usageCode = Str::upper($usageCode);
        $key = $genreCode . '|' . $usageCode;

        if (isset($this->genreUsageIdsByKey[$key])) {
            return $this->genreUsageIdsByKey[$key];
        }

        $genre = $this->vehicleGenresByCode[$genreCode] ?? null;
        $usage = $this->usagesByCode[$usageCode] ?? null;

        if (!$genre || !$usage) {
            return null;
        }

        $vehicleGenreUsage = VehicleGenreUsage::create([
            'vehicle_genre_id' => $genre->id,
            'usage_id' => $usage->id,
            'max_mileage_essence_per_year' => $genre->max_mileage_essence_per_year,
            'max_mileage_diesel_per_year' => $genre->max_mileage_diesel_per_year,
            'status_id' => $this->statusId,
            'created_by' => $this->userId,
            'updated_by' => $this->userId,
        ]);

        $this->genreUsageIdsByKey[$key] = $vehicleGenreUsage->id;
        $this->createdGenreUsageKeys[] = $key;

        return $vehicleGenreUsage->id;
    }

    private function resolveGenreCode(array $row): ?string
    {
        $genreRaw = $row['GenreVehicule'] ?? null;
        if ($genreRaw === null || trim((string) $genreRaw) === '') {
            $genreRaw = $row['GenreVehicule_Predit'] ?? null;
        }

        $normalized = $this->normalizeGenreLabel(is_string($genreRaw) || is_numeric($genreRaw) ? (string) $genreRaw : null);

        if ($normalized === '') {
            return $this->extractGenreCodeFromUsage($row['Usage'] ?? null);
        }

        $pair = self::GENRE_USAGE_MAP[$normalized] ?? null;

        return $pair[0] ?? null;
    }

    /**
     * Split Usage by "/", match each part to Usage.code or Usage.label (and aliases).
     * Creates missing Usage rows when a token cannot be resolved.
     *
     * @return list<string> usage codes
     */
    private function resolveUsageCodes(array $row, ?string $genreCode): array
    {
        $usageRaw = isset($row['Usage']) ? trim((string) $row['Usage']) : '';

        if ($usageRaw === '') {
            $genreRaw = $row['GenreVehicule'] ?? null;
            if ($genreRaw === null || trim((string) $genreRaw) === '') {
                $genreRaw = $row['GenreVehicule_Predit'] ?? null;
            }
            $normalized = $this->normalizeGenreLabel(is_string($genreRaw) || is_numeric($genreRaw) ? (string) $genreRaw : null);
            $pair = self::GENRE_USAGE_MAP[$normalized] ?? null;

            return $pair ? [$pair[1]] : [];
        }

        $tokens = $this->tokenizeUsageField($usageRaw);
        $usageCodes = [];

        foreach ($tokens as $token) {
            $usage = $this->resolveUsageModel($token);

            if (!$usage) {
                $upper = Str::upper(trim($token));
                if (in_array($upper, self::VEHICLE_GENRE_CODES, true) && !isset($this->usagesByCode[$upper])) {
                    continue;
                }

                $usage = $this->createUsageFromToken($token);
            }

            if (!$usage) {
                $this->unmatchedUsageTokens[$token] = ($this->unmatchedUsageTokens[$token] ?? 0) + 1;
                continue;
            }

            $usageCodes[] = Str::upper((string) $usage->code);
        }

        return array_values(array_unique($usageCodes));
    }

    /**
     * Tokenize Usage string on "/" while keeping labels that contain "/" (e.g. "Privé / Personnel").
     * "Location" and "Utilitaire" stay separate (UTIL / Location), they are not merged into LOUT.
     *
     * @return list<string>
     */
    private function tokenizeUsageField(string $usageRaw): array
    {
        $parts = array_values(array_filter(array_map('trim', explode('/', $usageRaw)), fn ($p) => $p !== ''));
        $tokens = [];
        $i = 0;
        $count = count($parts);

        while ($i < $count) {
            if ($i + 1 < $count) {
                $left = $this->normalizeLookupKey($parts[$i]);
                $right = $this->normalizeLookupKey($parts[$i + 1]);
                $isLocationUtilitairePair = ($left === 'location' && $right === 'utilitaire')
                    || ($left === 'utilitaire' && $right === 'location');

                // Ne pas fusionner Location + Utilitaire : ce sont 2 usages distincts (cf. UsageSeeder UTIL).
                if (!$isLocationUtilitairePair) {
                    $pairWithSlash = trim($parts[$i] . ' / ' . $parts[$i + 1]);
                    $pairCollapsed = trim($parts[$i] . '/' . $parts[$i + 1]);

                    if (
                        $this->resolveUsageModel($pairWithSlash)
                        || $this->resolveUsageModel($pairCollapsed)
                    ) {
                        $tokens[] = $pairWithSlash;
                        $i += 2;
                        continue;
                    }
                }
            }

            $tokens[] = $parts[$i];
            $i++;
        }

        return $tokens;
    }

    private function resolveUsageModel(string $token): ?Usage
    {
        $trimmed = trim($token);
        if ($trimmed === '') {
            return null;
        }

        $upper = Str::upper($trimmed);
        if (isset($this->usagesByCode[$upper])) {
            return $this->usagesByCode[$upper];
        }

        $normalized = $this->normalizeLookupKey($trimmed);
        if ($normalized === '') {
            return null;
        }

        if (isset($this->usagesByNormalizedLabel[$normalized])) {
            return $this->usagesByNormalizedLabel[$normalized];
        }

        $collapsed = preg_replace('/\s*\/\s*/', '/', $normalized) ?? $normalized;
        if (isset($this->usagesByNormalizedLabel[$collapsed])) {
            return $this->usagesByNormalizedLabel[$collapsed];
        }

        $spacedSlash = preg_replace('/\s*\/\s*/', ' / ', $normalized) ?? $normalized;
        if (isset($this->usagesByNormalizedLabel[$spacedSlash])) {
            return $this->usagesByNormalizedLabel[$spacedSlash];
        }

        $aliasCode = self::USAGE_ALIASES[$normalized]
            ?? self::USAGE_ALIASES[$collapsed]
            ?? self::USAGE_ALIASES[str_replace(' / ', ' ', $normalized)]
            ?? null;

        if ($aliasCode !== null && isset($this->usagesByCode[Str::upper($aliasCode)])) {
            return $this->usagesByCode[Str::upper($aliasCode)];
        }

        return null;
    }

    private function createUsageFromToken(string $token): ?Usage
    {
        $label = trim($token);
        if ($label === '') {
            return null;
        }

        $codeBase = Str::upper(substr(preg_replace('/[^A-Za-z0-9]/', '', Str::ascii($label)) ?: 'USG', 0, 4));
        if ($codeBase === '') {
            $codeBase = 'USG';
        }

        $code = $codeBase;
        $suffix = 1;
        while (isset($this->usagesByCode[$code]) || Usage::withTrashed()->where('code', $code)->exists()) {
            $suffixPart = (string) $suffix++;
            $code = Str::upper(substr($codeBase, 0, max(1, 4 - strlen($suffixPart))) . $suffixPart);
        }

        $usage = Usage::create([
            'code' => $code,
            'label' => $label,
            'description' => $label,
            'status_id' => $this->statusId,
            'created_by' => $this->userId,
            'updated_by' => $this->userId,
        ]);

        $this->indexUsage($usage);
        $this->createdUsageCodes[] = $code . ' (' . $label . ')';

        return $usage;
    }

    private function extractGenreCodeFromUsage(?string $usageRaw): ?string
    {
        if ($usageRaw === null || trim($usageRaw) === '') {
            return null;
        }

        foreach (array_map('trim', explode('/', $usageRaw)) as $part) {
            $upper = Str::upper($part);
            if (in_array($upper, self::VEHICLE_GENRE_CODES, true) && isset($this->vehicleGenresByCode[$upper])) {
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

        $normalized = $this->normalizeLookupKey($value);
        $normalized = str_replace('camion plus de 5t', 'camion plus de 5 t', $normalized);
        $normalized = str_replace('camion 2.5t a 5t', 'camion 2,5 t a 5 t', $normalized);

        return $normalized;
    }

    private function normalizeLookupKey(?string $value): string
    {
        if ($value === null) {
            return '';
        }

        $normalized = Str::lower(trim(str_replace(["\r", "\n"], '', (string) $value)));
        $normalized = Str::ascii($normalized);
        $normalized = preg_replace('/\s+/', ' ', $normalized) ?? $normalized;

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
