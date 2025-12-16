<?php

namespace Database\Seeders;

use App\Models\Usage;
use App\Models\VehicleGenre;
use App\Models\VehicleEnergy;
use Illuminate\Database\Seeder;
use App\Models\VehicleCharacteristic;

class VehicleCharacteristicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        VehicleCharacteristic::create([
            'vehicle_model_id' => 1,
            'vehicle_genre_usage_id' => 1,
            'vehicle_energy_id' => VehicleEnergy::where('code', 'VE01')->first()->id,
            'dealer_id' => 1,
            'type' => 'Berline',
            'options' => 'options 1',
            'fiscal_power' => 100,
            'nb_seats' => 5,
            'new_market_value' => 10000000,
            'status_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        VehicleCharacteristic::create([
            'vehicle_model_id' => 1,
            'vehicle_genre_usage_id' => 2,
            'vehicle_energy_id' => VehicleEnergy::where('code', 'VE02')->first()->id,
            'dealer_id' => 2,
            'type' => 'SUV',
            'options' => 'options 2',
            'fiscal_power' => 200,
            'nb_seats' => 7,
            'new_market_value' => 20000000,
            'status_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);
    }
}
