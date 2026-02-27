<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Status;
use App\Enums\StatusEnum;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BrandListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Seeds brands from data/liste_marques.json using LibMarque as label.
     * Duplicate LibMarque values in the file are ignored (one brand per unique label).
     *
     * @return void
     */
    public function run(): void
    {
        $path = base_path('data/liste_marques.json');

        if (!file_exists($path)) {
            $this->command->warn("File not found: {$path}");
            return;
        }

        $data = json_decode(file_get_contents($path), true);

        if (!is_array($data)) {
            $this->command->warn('Invalid JSON in liste_marques.json');
            return;
        }

        $statusId = Status::firstWhere('code', StatusEnum::ACTIVE)?->id;
        $userId = 1;

        foreach ($data as $row) {
            $label = isset($row['LibMarque']) ? trim((string) $row['LibMarque']) : '';

            if ($label === '') {
                continue;
            }

            $code = Str::slug($label) ?: ('marque-' . ($row['IdMarque'] ?? 0));

            Brand::updateOrCreate(
                ['code' => $code],
                [
                    'label' => $label,
                    'description' => $label,
                    'status_id' => $statusId,
                    'created_by' => $userId,
                    'updated_by' => $userId,
                ]
            );
        }
    }
}
