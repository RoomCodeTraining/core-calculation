<?php

namespace Database\Seeders;

use App\Models\Energy;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EnergySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Energy::create([
            'code' => 'VE01',
            'label' => 'ESSENCE',
            'description' => 'ESSENCE',
        ]);

        Energy::create([
            'code' => 'VE02',
            'label' => 'GASOIL',
            'description' => 'GASOIL',
        ]);

        Energy::create([
            'code' => 'VE03',
            'label' => 'ELECTRIQUE',
            'description' => 'ELECTRIQUE',
        ]);

        Energy::create([
            'code' => 'VE04',
            'label' => 'HYBRIDE',
            'description' => 'HYBRIDE',
        ]);

    }
}
