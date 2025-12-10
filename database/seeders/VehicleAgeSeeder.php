<?php

namespace Database\Seeders;

use App\Models\VehicleAge;
use Illuminate\Database\Seeder;

class VehicleAgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Créer les âges de véhicule de 1 à 120 mois (10 ans)
        for ($i = 1; $i <= 120; $i++) {
            VehicleAge::create([
                'value' => $i,
                'label' => $i . ' mois',
                'description' => $i . ' mois',
            ]);
        }
    }
}

