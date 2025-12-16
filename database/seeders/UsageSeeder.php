<?php

namespace Database\Seeders;

use App\Models\Usage;
use App\Models\VehicleGenre;
use Illuminate\Database\Seeder;

class UsageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        Usage::create(
            [
                'code' => 'US01',
                'label' => 'Promenade ou Affaire',
                'description' => 'Promenade ou Affaire',
                'status_id' => 1,
                'created_by' => 1,
                'updated_by' => 1,
            ]
        );

        Usage::create(
            [
                'code' => 'US02',
                'label' => 'Transport pour propre compte',
                'description' => 'Transport pour propre compte',
                'status_id' => 1,
                'created_by' => 1,
                'updated_by' => 1,
            ]
        );
    }
}
