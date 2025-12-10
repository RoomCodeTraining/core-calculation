<?php

namespace Database\Seeders;

use App\Models\VehicleAge;
use App\Models\Genre;
use Illuminate\Database\Seeder;
use App\Models\DepreciationTable;

class DepreciationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Récupérer tous les genres (usages) et âges de véhicules
        $genres = Genre::whereNotNull('code')->get();
        $vehicleAges = VehicleAge::all();

        // Tableau de valeurs de dépréciation par usage et par tranche d'âge
        $depreciationValues = [
            'VG01' => [ // CYCLO MOTO 5000 Km/An
                'base' => 1.66,
                'increment' => 0.15, // Augmentation mensuelle
            ],
            'VG02' => [ // TAXI COMPTEUR
                'base' => 2.50,
                'increment' => 0.20,
            ],
            'VG03' => [ // VTC
                'base' => 2.30,
                'increment' => 0.18,
            ],
            'VG04' => [ // VÉHICULE PARTICULIER
                'base' => 1.50,
                'increment' => 0.12,
            ],
            'VG05' => [ // VÉHICULE UTILITAIRE
                'base' => 1.80,
                'increment' => 0.14,
            ],
            'VG06' => [ // CAMIONNETTE DE 2.5 T à 5 T
                'base' => 2.20,
                'increment' => 0.16,
            ],
            'VG07' => [ // CAMION DE + 5 T
                'base' => 2.00,
                'increment' => 0.15,
            ],
            'VG08' => [ // BUS/TPV/MINIBUS, AUTOCAR
                'base' => 2.40,
                'increment' => 0.17,
            ],
            'VG09' => [ // TRACTEUR ROUTIER
                'base' => 2.10,
                'increment' => 0.16,
            ],
            'VG10' => [ // SEMI-REMORQUE
                'base' => 2.00,
                'increment' => 0.15,
            ],
            'VG11' => [ // PETIT ENGIN
                'base' => 1.20,
                'increment' => 0.10,
            ],
            'VG12' => [ // GROS ENGIN
                'base' => 1.00,
                'increment' => 0.08,
            ],
            'VG13' => [ // CITERNE
                'base' => 1.50,
                'increment' => 0.12,
            ],
        ];

        // Créer les données de dépréciation pour chaque combinaison
        foreach ($genres as $genre) {
            if (!isset($depreciationValues[$genre->code])) {
                continue;
            }

            $config = $depreciationValues[$genre->code];
            $baseValue = $config['base'];
            $increment = $config['increment'];

            foreach ($vehicleAges as $vehicleAge) {
                // Calculer la valeur de dépréciation en fonction de l'âge
                // La dépréciation augmente progressivement avec l'âge
                $age = $vehicleAge->value;

                // Formule: base + (âge - 1) * increment
                // Pour les premiers mois, dépréciation plus faible
                // Pour les mois suivants, dépréciation progressive
                if ($age <= 12) {
                    // Première année: dépréciation plus rapide
                    $value = $baseValue + ($age - 1) * ($increment * 1.2);
                } elseif ($age <= 60) {
                    // 2 à 5 ans: dépréciation régulière
                    $value = $baseValue + 11 * ($increment * 1.2) + ($age - 12) * $increment;
                } else {
                    // Plus de 5 ans: dépréciation ralentie
                    $value = $baseValue + 11 * ($increment * 1.2) + 48 * $increment + ($age - 60) * ($increment * 0.8);
                }

                // Arrondir à 2 décimales
                $value = round($value, 2);

                DepreciationTable::firstOrCreate(
                    [
                        'usage_id' => $genre->id,
                        'vehicle_age_id' => $vehicleAge->id,
                    ],
                    [
                        'value' => $value,
                    ]
                );
            }
        }
    }
}

