<?php

namespace Database\Seeders;

use App\Models\VehicleCharacteristicGenreUsage;
use Illuminate\Database\Seeder;

class VehicleCharacteristicGenreUsageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        VehicleCharacteristicGenreUsage::factory(10)->create();
    }
}
