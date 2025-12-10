<?php

namespace Database\Factories;

use App\Models\DepreciationTable;
use App\Models\Genre;
use App\Models\VehicleAge;
use Illuminate\Database\Eloquent\Factories\Factory;

class DepreciationTableFactory extends Factory
{
    protected $model = DepreciationTable::class;

    public function definition(): array
    {
        return [
            'usage_id' => Genre::factory(),
            'vehicle_age_id' => VehicleAge::factory(),
            'value' => $this->faker->randomFloat(2, 0, 100),
        ];
    }
}

