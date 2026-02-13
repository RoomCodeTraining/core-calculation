<?php

namespace Database\Seeders;

use App\Models\Usage;
use App\Models\VehicleGenre;
use Illuminate\Database\Seeder;

class UsageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $usages = [
            ['code' => 'US01', 'label' => 'Privé / Personnel', 'description' => 'Privé / Personnel', 'status_id' => 1],
            ['code' => 'US02', 'label' => 'Professionnel léger', 'description' => 'Professionnel léger', 'status_id' => 1],
            ['code' => 'US03', 'label' => 'VTC', 'description' => 'VTC (Véhicule de Transport avec Chauffeur)', 'status_id' => 1],
            ['code' => 'US04', 'label' => 'Taxi compteur', 'description' => 'Taxi compteur', 'status_id' => 1],
            ['code' => 'US05', 'label' => 'Auto-école', 'description' => 'Auto-école', 'status_id' => 1],
            ['code' => 'US06', 'label' => 'Location courte durée', 'description' => 'Location courte durée', 'status_id' => 1],
            ['code' => 'US07', 'label' => 'Location longue durée ', 'description' => 'Location longue durée ', 'status_id' => 1],
            ['code' => 'US08', 'label' => 'Service administratif', 'description' => 'Service administratif', 'status_id' => 1],
            ['code' => 'US09', 'label' => 'Privé', 'description' => 'Privé', 'status_id' => 1],
            ['code' => 'US10', 'label' => 'Transport marchandises', 'description' => 'Transport marchandises', 'status_id' => 1],
            ['code' => 'US11', 'label' => 'Chantier', 'description' => 'Chantier', 'status_id' => 1],
            ['code' => 'US12', 'label' => 'Service technique', 'description' => 'Service technique', 'status_id' => 1],
            ['code' => 'US13', 'label' => 'Location utilitaire', 'description' => 'Location utilitaire', 'status_id' => 1],
            ['code' => 'US14', 'label' => 'Transport marchandises générales', 'description' => 'Transport marchandises générales', 'status_id' => 1],
            ['code' => 'US15', 'label' => 'Transport matériaux', 'description' => 'Transport matériaux', 'status_id' => 1],
            ['code' => 'US16', 'label' => 'Transport frigorifique', 'description' => 'Transport frigorifique', 'status_id' => 1],
            ['code' => 'US17', 'label' => 'Transport hydrocarbures', 'description' => 'Transport hydrocarbures', 'status_id' => 1],
            ['code' => 'US18', 'label' => 'BTP', 'description' => 'BTP', 'status_id' => 1],
            ['code' => 'US19', 'label' => 'Location industrielle', 'description' => 'Location industrielle', 'status_id' => 1],
            ['code' => 'US20', 'label' => 'Transport urbain', 'description' => 'Transport urbain', 'status_id' => 1],
            ['code' => 'US21', 'label' => 'Transport interurbain', 'description' => 'Transport interurbain', 'status_id' => 1],
            ['code' => 'US22', 'label' => 'Transport scolaire', 'description' => 'Transport scolaire', 'status_id' => 1],
            ['code' => 'US23', 'label' => 'Transport touristique', 'description' => 'Transport touristique', 'status_id' => 1],
            ['code' => 'US24', 'label' => 'Transport entreprise', 'description' => 'Transport entreprise', 'status_id' => 1],
            ['code' => 'US25', 'label' => 'Livraison', 'description' => 'Livraison', 'status_id' => 1],
            ['code' => 'US26', 'label' => 'Taxi moto', 'description' => 'Taxi moto', 'status_id' => 1],
            ['code' => 'US27', 'label' => 'Transport matériel', 'description' => 'Transport matériel', 'status_id' => 1],
            ['code' => 'US28', 'label' => 'Transport agricole', 'description' => 'Transport agricole', 'status_id' => 1],
            ['code' => 'US29', 'label' => 'Transport chantier', 'description' => 'Transport chantier', 'status_id' => 1],
            ['code' => 'US30', 'label' => 'Transport longue distance', 'description' => 'Transport longue distance', 'status_id' => 1],
            ['code' => 'US31', 'label' => 'Transport international', 'description' => 'Transport international', 'status_id' => 1],
            ['code' => 'US32', 'label' => 'Transport conteneurs', 'description' => 'Transport conteneurs', 'status_id' => 1],
            ['code' => 'US33', 'label' => 'Chantier BTP', 'description' => 'Chantier BTP', 'status_id' => 1],
            ['code' => 'US34', 'label' => 'Manutention', 'description' => 'Manutention', 'status_id' => 1],
            ['code' => 'US35', 'label' => 'Exploitation minière', 'description' => 'Exploitation minière', 'status_id' => 1],
            ['code' => 'US36', 'label' => 'Travaux publics', 'description' => 'Travaux publics', 'status_id' => 1],
            ['code' => 'US37', 'label' => 'Agriculture', 'description' => 'Agriculture', 'status_id' => 1],
            ['code' => 'US38', 'label' => 'Ambulance', 'description' => 'Ambulance', 'status_id' => 1],
            ['code' => 'US39', 'label' => 'Dépanneuse', 'description' => 'Dépanneuse', 'status_id' => 1],
            ['code' => 'US40', 'label' => 'Corbillard', 'description' => 'Corbillard', 'status_id' => 1],
            ['code' => 'US41', 'label' => 'Atelier mobile', 'description' => 'Atelier mobile', 'status_id' => 1],
            ['code' => 'US42', 'label' => 'Véhicule incendie', 'description' => 'Véhicule incendie', 'status_id' => 1],
            ['code' => 'US43', 'label' => 'Véhicule blindé', 'description' => 'Véhicule blindé', 'status_id' => 1],
            ['code' => 'US44', 'label' => 'Citernes', 'description' => 'Citernes', 'status_id' => 1],
        ];

        Usage::upsert(
            $usages,
            ['code'],
            ['label', 'description', 'status_id']
        );
    }
}
