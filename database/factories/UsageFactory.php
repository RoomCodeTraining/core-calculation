<?php

namespace Database\Factories;

use App\Models\Usage;
use Illuminate\Database\Eloquent\Factories\Factory;

class UsageFactory extends Factory
{
    protected $model = Usage::class;

    public function definition(): array
    {
        return [
            'code' => 'VG' . $this->faker->unique()->numberBetween(1, 999),
            'label' => $this->faker->words(3, true),
            'max_mileage_essence_per_year' => $this->faker->randomFloat(2, 5000, 30000),
            'max_mileage_diesel_per_year' => $this->faker->randomFloat(2, 5000, 30000),
            'description' => $this->faker->sentence(),
        ];
    }
}

