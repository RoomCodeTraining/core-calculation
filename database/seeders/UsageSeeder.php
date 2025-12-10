<?php

namespace Database\Seeders;

use App\Models\Genre;
use App\Models\Usage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UsageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $usages = [
            "Promenade ou Affaire",
            "Transport pour propre compte",
            "Transport privé de voyageurs",
            "Transport public de marchandises",
            "Transport public de voyageurs",
            "Véhicules Auto-école",
            "Véhicules de Location",
            "Véhicules Spéciaux",
            "Engin de Chantier",
            "Vehicule motorisé 2 roues à 3 roues",
        ];

        // Récupérer tous les genres existants
        $genres = Genre::all();

        foreach ($usages as $usageName) {
            foreach ($genres as $genre) {
                // Générer un code unique basé sur le nom de l'usage et le genre
                // Supprimer les accents et prendre les premières lettres en majuscules
                $usageClean = $this->cleanForCode($usageName);
                $genreClean = $this->cleanForCode($genre->name ?? $genre->code ?? 'G');

                $codePrefix = strtoupper(substr($usageClean, 0, 2));
                $genrePrefix = strtoupper(substr($genreClean, 0, 1));
                $baseCode = $codePrefix . $genrePrefix;

                // Vérifier si le code existe déjà et générer un nouveau si nécessaire
                $code = $baseCode;
                $counter = 1;
                while (Usage::where('code', $code)->exists()) {
                    $code = $baseCode . $counter;
                    $counter++;
                }

                // Utiliser firstOrCreate pour éviter les doublons
                Usage::firstOrCreate(
                    [
                        'genre_id' => $genre->id,
                        'slug' => Str::slug($usageName),
                    ],
                    [
                        'name' => $usageName,
                        'code' => $code,
                        'max_mileage_essence_per_year' => rand(10000, 20000),
                        'max_mileage_diesel_per_year' => rand(10000, 20000),
                        'label' => $usageName,
                        'description' => $usageName,
                    ]
                );
            }
        }
    }

    /**
     * Nettoie une chaîne pour générer un code ASCII-safe
     */
    private function cleanForCode(string $text): string
    {
        // Supprimer les accents en utilisant Str::ascii()
        $cleaned = Str::ascii($text);

        // Garder seulement les lettres et chiffres
        $cleaned = preg_replace('/[^a-zA-Z0-9]/', '', $cleaned);

        return $cleaned;
    }
}




