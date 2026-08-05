<?php

namespace Database\Seeders;

use App\Models\Dealer;
use App\Models\Status;
use App\Enums\StatusEnum;
use Illuminate\Database\Seeder;

class DealerListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Seeds dealers from data/liste_concessionnaires.json using nomconcessionnaire as name.
     * Duplicate names in the file are ignored (one dealer per unique name).
     *
     * @return void
     */
    public function run(): void
    {
        $path = base_path('data/liste_concessionnaires.json');

        if (!file_exists($path)) {
            $this->command->warn("File not found: {$path}");
            return;
        }

        $data = json_decode(file_get_contents($path), true);

        if (!is_array($data)) {
            $this->command->warn('Invalid JSON in liste_concessionnaires.json');
            return;
        }

        $statusId = Status::firstWhere('code', StatusEnum::ACTIVE)?->id;
        $userId = 1;

        $uniqueNames = [];
        foreach ($data as $row) {
            $name = isset($row['nomconcessionnaire']) ? trim((string) $row['nomconcessionnaire']) : '';
            if ($name !== '') {
                $uniqueNames[$name] = true;
            }
        }

        foreach (array_keys($uniqueNames) as $name) {
            Dealer::updateOrCreate(
                ['name' => $name],
                [
                    'status_id' => $statusId,
                    'created_by' => $userId,
                    'updated_by' => $userId,
                ]
            );
        }
    }
}
