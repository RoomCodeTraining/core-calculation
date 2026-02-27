<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Status;
use App\Enums\StatusEnum;
use App\Models\VehicleModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class VehicleModelListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Seeds vehicle models from data/conseilauto_data_20260225_230502.json:
     * - NomCommercial → label
     * - Modele → description
     * - Marque → brand (lookup by label/code).
     * Duplicates (same brand + same NomCommercial) are ignored.
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

        $statusId = Status::firstWhere('code', StatusEnum::ACTIVE)?->id;
        $userId = 1;

        // Unique models: key = marque + NomCommercial, value = first Modele (description)
        $unique = [];
        foreach ($data as $row) {
            $marque = isset($row['Marque']) ? trim((string) $row['Marque']) : '';
            $label = isset($row['NomCommercial']) ? trim((string) $row['NomCommercial']) : '';
            $description = isset($row['Modele']) ? trim((string) $row['Modele']) : '';

            if ($marque === '' || $label === '') {
                continue;
            }

            $key = $marque . '|' . $label;
            if (!isset($unique[$key])) {
                $unique[$key] = [
                    'marque' => $marque,
                    'label' => $label,
                    'description' => $description,
                ];
            }
        }

        $this->command->info('Resolving brands and creating vehicle models...');
        $brandByCode = Brand::all()->keyBy('code');
        $brandByLabel = Brand::all()->keyBy(fn ($b) => strtoupper((string) $b->label));

        $created = 0;
        $skipped = 0;

        foreach ($unique as $item) {
            $marqueSlug = Str::slug($item['marque']);
            $brand = $brandByCode->get($marqueSlug)
                ?? $brandByLabel->get(strtoupper($item['marque']));

            if (!$brand) {
                $skipped++;
                continue;
            }

            $code = $marqueSlug . '-' . (Str::slug($item['label']) ?: 'model');

            VehicleModel::updateOrCreate(
                ['code' => $code],
                [
                    'label' => $item['label'],
                    'description' => $item['description'],
                    'brand_id' => $brand->id,
                    'status_id' => $statusId,
                    'created_by' => $userId,
                    'updated_by' => $userId,
                ]
            );
            $created++;
        }

        $this->command->info("Vehicle models: {$created} created/updated, {$skipped} skipped (brand not found).");
    }
}
