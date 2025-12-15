<?php

namespace Database\Seeders;

use App\Models\VehicleGenreUsage;
use App\Models\VehicleGenre;
use App\Models\Usage;
use Illuminate\Database\Seeder;

class VehicleGenreUsageSeeder extends Seeder
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
            'status_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        VehicleGenreUsage::create([
            'vehicle_genre_id' => VehicleGenre::where('code', 'VG04')->first()->id,
            'usage_id' => Usage::where('code', 'US02')->first()->id,
            'status_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);
    }
}
