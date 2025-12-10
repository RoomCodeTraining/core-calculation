<?php

namespace Database\Seeders;

use App\Models\Genre;
use App\Models\VehicleModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed vehicle genres (usages) - données de VehicleGenreSeeder
        $vehicleGenres = [
            [
                'code' => 'VG01',
                'max_mileage_essence_per_year' => '5000.00',
                'max_mileage_diesel_per_year' => '5000.00',
                'label' => 'CYCLO MOTO 5000 Km/An',
                'description' => 'CYCLO MOTO 5000 Km/An',
            ],
            [
                'code' => 'VG02',
                'max_mileage_essence_per_year' => '60000.00',
                'max_mileage_diesel_per_year' => '60000.00',
                'label' => 'TAXI COMPTEUR',
                'description' => 'TAXI COMPTEUR 60000 Km/An',
            ],
            [
                'code' => 'VG03',
                'max_mileage_essence_per_year' => '80000.00',
                'max_mileage_diesel_per_year' => '80000.00',
                'label' => 'VTC (Véhicule de Transport avec Chauffeur)',
                'description' => 'VTC (Véhicule de Transport avec Chauffeur) 80000 Km/An',
            ],
            [
                'code' => 'VG04',
                'max_mileage_essence_per_year' => '25000.00',
                'max_mileage_diesel_per_year' => '35000.00',
                'label' => 'VÉHICULE PARTICULIER',
                'description' => 'VÉHICULE PARTICULIER, SUV, 4*4, Essence 25000 Km/An, Diesel 35000 Km/An',
            ],
            [
                'code' => 'VG05',
                'max_mileage_essence_per_year' => '40000.00',
                'max_mileage_diesel_per_year' => '40000.00',
                'label' => 'VÉHICULE UTILITAIRE',
                'description' => 'VÉHICULE UTILITAIRE / PU / CTTE ET 4*4 FGON 40000 Km/An',
            ],
            [
                'code' => 'VG06',
                'max_mileage_essence_per_year' => '70000.00',
                'max_mileage_diesel_per_year' => '70000.00',
                'label' => 'CAMIONNETTE DE 2.5 T à 5 T',
                'description' => 'CAMIONNETTE DE 2.5 T à 5 T, 700000 Km/An',
            ],
            [
                'code' => 'VG07',
                'max_mileage_essence_per_year' => '60000.00',
                'max_mileage_diesel_per_year' => '60000.00',
                'label' => 'CAMION DE + 5 T',
                'description' => 'CAMION DE + 5 T, 600000 Km/An',
            ],
            [
                'code' => 'VG08',
                'max_mileage_essence_per_year' => '100000.00',
                'max_mileage_diesel_per_year' => '100000.00',
                'label' => 'BUS/TPV/MINIBUS, AUTOCAR',
                'description' => 'BUS/TPV/MINIBUS, AUTOCAR, 100000 Km/An',
            ],
            [
                'code' => 'VG09',
                'max_mileage_essence_per_year' => '80000.00',
                'max_mileage_diesel_per_year' => '80000.00',
                'label' => 'TRACTEUR ROUTIER',
                'description' => 'TRACTEUR ROUTIER, 80000 Km/An',
            ],
            [
                'code' => 'VG10',
                'max_mileage_essence_per_year' => '80000.00',
                'max_mileage_diesel_per_year' => '80000.00',
                'label' => 'SEMI-REMORQUE',
                'description' => 'SEMI-REMORQUE',
            ],
            [
                'code' => 'VG11',
                'max_mileage_essence_per_year' => '0.00',
                'max_mileage_diesel_per_year' => '0.00',
                'label' => 'PETIT ENGIN',
                'description' => 'PETIT ENGIN',
            ],
            [
                'code' => 'VG12',
                'max_mileage_essence_per_year' => '0.00',
                'max_mileage_diesel_per_year' => '0.00',
                'label' => 'GROS ENGIN',
                'description' => 'GROS ENGIN',
            ],
            [
                'code' => 'VG13',
                'max_mileage_essence_per_year' => '0.00',
                'max_mileage_diesel_per_year' => '0.00',
                'label' => 'CITERNE',
                'description' => 'CITERNE',
            ],
        ];

        // Créer les genres de véhicules (usages)
        foreach ($vehicleGenres as $vehicleGenre) {
            Genre::firstOrCreate(
                ['code' => $vehicleGenre['code']],
                $vehicleGenre
            );
        }

        // Seed genres liés aux modèles de véhicules
        $genres = [
            'Sedan' => ['Corolla', 'Camry', 'Civic', 'Accord', 'Passat', 'Jetta', 'Altima', 'Sentra', 'Elantra', 'Sonata', 'Forte', 'Optima', 'A3', 'A4', 'A6', '3 Series', '5 Series', '7 Series', 'C-Class', 'E-Class', 'S-Class', 'Maxima'],
            'SUV' => ['RAV4', 'Highlander', 'CR-V', 'Pilot', 'Explorer', 'Escape', 'X3', 'X5', 'GLC', 'GLE', 'Tiguan', 'Atlas', 'Rogue', 'Pathfinder', 'Tucson', 'Santa Fe', 'Kona', 'Sportage', 'Sorento', 'Telluride', 'Q5', 'Q7'],
            'Coupe' => ['Mustang'],
            'Hatchback' => ['Prius', 'Fit', 'Golf', 'Focus'],
            'Truck' => ['F-150'],
        ];

        foreach ($genres as $genreName => $modelNames) {
            foreach ($modelNames as $modelName) {
                $vehicleModel = VehicleModel::where('name', $modelName)->first();

                if ($vehicleModel) {
                    // Check if genre already exists for this model
                    $existingGenre = Genre::where('vehicle_model_id', $vehicleModel->id)
                        ->where('slug', Str::slug($genreName))
                        ->first();

                    if (!$existingGenre) {
                        Genre::create([
                            'vehicle_model_id' => $vehicleModel->id,
                            'name' => $genreName,
                            'slug' => Str::slug($genreName),
                            // code, max_mileage_*, label sont NULL pour les genres liés aux modèles
                        ]);
                    }
                }
            }
        }
    }
}


