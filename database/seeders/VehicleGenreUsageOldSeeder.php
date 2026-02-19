<?php

namespace Database\Seeders;

use App\Models\VehicleGenreUsage;
use App\Models\VehicleGenre;
use App\Models\Usage;
use Illuminate\Database\Seeder;

class VehicleGenreUsageISeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        VehicleGenreUsage::create([
            'vehicle_genre_id' => VehicleGenre::where('code', 'VG04')->first()->id,
            'usage_id' => Usage::where('code', 'US01')->first()->id,
            'max_mileage_essence_per_year' => 5000,
            'max_mileage_diesel_per_year' => 5000,
            'status_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        VehicleGenreUsage::create([
            'vehicle_genre_id' => VehicleGenre::where('code', 'VG04')->first()->id,
            'usage_id' => Usage::where('code', 'US02')->first()->id,
            'max_mileage_essence_per_year' => 10000,
            'max_mileage_diesel_per_year' => 10000,
            'status_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);
    }
}
