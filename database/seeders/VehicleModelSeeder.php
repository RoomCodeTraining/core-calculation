<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\VehicleModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class VehicleModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $models = [
            'Toyota' => ['Corolla', 'Camry', 'RAV4', 'Highlander', 'Prius'],
            'Honda' => ['Civic', 'Accord', 'CR-V', 'Pilot', 'Fit'],
            'Ford' => ['F-150', 'Mustang', 'Explorer', 'Escape', 'Focus'],
            'BMW' => ['3 Series', '5 Series', 'X3', 'X5', '7 Series'],
            'Mercedes-Benz' => ['C-Class', 'E-Class', 'S-Class', 'GLC', 'GLE'],
            'Volkswagen' => ['Golf', 'Passat', 'Tiguan', 'Jetta', 'Atlas'],
            'Audi' => ['A3', 'A4', 'A6', 'Q5', 'Q7'],
            'Nissan' => ['Altima', 'Sentra', 'Rogue', 'Pathfinder', 'Maxima'],
            'Hyundai' => ['Elantra', 'Sonata', 'Tucson', 'Santa Fe', 'Kona'],
            'Kia' => ['Forte', 'Optima', 'Sportage', 'Sorento', 'Telluride'],
        ];

        foreach ($models as $brandName => $modelNames) {
            $brand = Brand::where('name', $brandName)->first();

            if ($brand) {
                foreach ($modelNames as $modelName) {
                    VehicleModel::create([
                        'brand_id' => $brand->id,
                        'name' => $modelName,
                        'slug' => Str::slug($modelName),
                    ]);
                }
            }
        }
    }
}




