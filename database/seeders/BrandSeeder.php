<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            ['name' => 'Toyota'],
            ['name' => 'Honda'],
            ['name' => 'Ford'],
            ['name' => 'BMW'],
            ['name' => 'Mercedes-Benz'],
            ['name' => 'Volkswagen'],
            ['name' => 'Audi'],
            ['name' => 'Nissan'],
            ['name' => 'Hyundai'],
            ['name' => 'Kia'],
        ];

        foreach ($brands as $brand) {
            Brand::create([
                'name' => $brand['name'],
                'slug' => Str::slug($brand['name']),
            ]);
        }
    }
}









