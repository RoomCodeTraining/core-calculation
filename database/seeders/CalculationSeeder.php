<?php

namespace Database\Seeders;

use App\Models\Calculation;
use Illuminate\Database\Seeder;

class CalculationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        Calculation::factory(10)->create();
    }
}
