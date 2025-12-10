<?php

namespace Database\Factories;

use App\Models\VehicleAge;
use Illuminate\Database\Eloquent\Factories\Factory;

class VehicleAgeFactory extends Factory
{
    protected $model = VehicleAge::class;

    public function definition(): array
    {
        return [
            'value' => $this->faker->unique()->numberBetween(1, 120),
            'label' => $this->faker->numberBetween(1, 120) . ' mois',
            'description' => $this->faker->sentence(),
        ];
    }
}

