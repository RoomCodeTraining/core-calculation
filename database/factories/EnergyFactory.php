<?php

namespace Database\Factories;

use App\Models\Energy;
use Illuminate\Database\Eloquent\Factories\Factory;

class EnergyFactory extends Factory
{
    protected $model = Energy::class;

    public function definition(): array
    {
        return [
            'code' => 'VE' . $this->faker->unique()->numberBetween(1, 99),
            'label' => $this->faker->words(2, true),
        ];
    }
}

