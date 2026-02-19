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
            ['code' => 'PRIP', 'label' => 'Privé / Personnel', 'description' => 'Privé / Personnel', 'status_id' => 1],
            ['code' => 'PROF', 'label' => 'Professionnel léger', 'description' => 'Professionnel léger', 'status_id' => 1],
            ['code' => 'VTC', 'label' => 'VTC', 'description' => 'VTC (Véhicule de Transport avec Chauffeur)', 'status_id' => 1],
            ['code' => 'TAXI', 'label' => 'Taxi compteur', 'description' => 'Taxi compteur', 'status_id' => 1],
            ['code' => 'AUTO', 'label' => 'Auto-école', 'description' => 'Auto-école', 'status_id' => 1],
            ['code' => 'LOCD', 'label' => 'Location courte durée', 'description' => 'Location courte durée', 'status_id' => 1],
            ['code' => 'LOLD', 'label' => 'Location longue durée ', 'description' => 'Location longue durée ', 'status_id' => 1],
            ['code' => 'SADM', 'label' => 'Service administratif', 'description' => 'Service administratif', 'status_id' => 1],
            ['code' => 'PRIV', 'label' => 'Privé', 'description' => 'Privé', 'status_id' => 1],
            ['code' => 'TRMA', 'label' => 'Transport marchandises', 'description' => 'Transport marchandises', 'status_id' => 1],
            ['code' => 'CHAN', 'label' => 'Chantier', 'description' => 'Chantier', 'status_id' => 1],
            ['code' => 'STEC', 'label' => 'Service technique', 'description' => 'Service technique', 'status_id' => 1],
            ['code' => 'LOUT', 'label' => 'Location utilitaire', 'description' => 'Location utilitaire', 'status_id' => 1],
            ['code' => 'TMAG', 'label' => 'Transport marchandises générales', 'description' => 'Transport marchandises générales', 'status_id' => 1],
            ['code' => 'TMAT', 'label' => 'Transport matériaux', 'description' => 'Transport matériaux', 'status_id' => 1],
            ['code' => 'TFRG', 'label' => 'Transport frigorifique', 'description' => 'Transport frigorifique', 'status_id' => 1],
            ['code' => 'THYC', 'label' => 'Transport hydrocarbures', 'description' => 'Transport hydrocarbures', 'status_id' => 1],
            ['code' => 'BTP', 'label' => 'BTP', 'description' => 'BTP', 'status_id' => 1],
            ['code' => 'LOIN', 'label' => 'Location industrielle', 'description' => 'Location industrielle', 'status_id' => 1],
            ['code' => 'TURB', 'label' => 'Transport urbain', 'description' => 'Transport urbain', 'status_id' => 1],
            ['code' => 'TINR', 'label' => 'Transport interurbain', 'description' => 'Transport interurbain', 'status_id' => 1],
            ['code' => 'TSCO', 'label' => 'Transport scolaire', 'description' => 'Transport scolaire', 'status_id' => 1],
            ['code' => 'TTOU', 'label' => 'Transport touristique', 'description' => 'Transport touristique', 'status_id' => 1],
            ['code' => 'TENT', 'label' => 'Transport entreprise', 'description' => 'Transport entreprise', 'status_id' => 1],
            ['code' => 'LIVR', 'label' => 'Livraison', 'description' => 'Livraison', 'status_id' => 1],
            ['code' => 'TAMO', 'label' => 'Taxi moto', 'description' => 'Taxi moto', 'status_id' => 1],
            ['code' => 'TMEL', 'label' => 'Transport matériel', 'description' => 'Transport matériel', 'status_id' => 1],
            ['code' => 'TAGR', 'label' => 'Transport agricole', 'description' => 'Transport agricole', 'status_id' => 1],
            ['code' => 'TCHA', 'label' => 'Transport chantier', 'description' => 'Transport chantier', 'status_id' => 1],
            ['code' => 'TLGD', 'label' => 'Transport longue distance', 'description' => 'Transport longue distance', 'status_id' => 1],
            ['code' => 'TINT', 'label' => 'Transport international', 'description' => 'Transport international', 'status_id' => 1],
            ['code' => 'TCON', 'label' => 'Transport conteneurs', 'description' => 'Transport conteneurs', 'status_id' => 1],
            ['code' => 'CBTP', 'label' => 'Chantier BTP', 'description' => 'Chantier BTP', 'status_id' => 1],
            ['code' => 'MANU', 'label' => 'Manutention', 'description' => 'Manutention', 'status_id' => 1],
            ['code' => 'EXMI', 'label' => 'Exploitation minière', 'description' => 'Exploitation minière', 'status_id' => 1],
            ['code' => 'TRPU', 'label' => 'Travaux publics', 'description' => 'Travaux publics', 'status_id' => 1],
            ['code' => 'AGRI', 'label' => 'Agriculture', 'description' => 'Agriculture', 'status_id' => 1],
            ['code' => 'AMBU', 'label' => 'Ambulance', 'description' => 'Ambulance', 'status_id' => 1],
            ['code' => 'DEPN', 'label' => 'Dépanneuse', 'description' => 'Dépanneuse', 'status_id' => 1],
            ['code' => 'CORB', 'label' => 'Corbillard', 'description' => 'Corbillard', 'status_id' => 1],
            ['code' => 'ATMO', 'label' => 'Atelier mobile', 'description' => 'Atelier mobile', 'status_id' => 1],
            ['code' => 'VINC', 'label' => 'Véhicule incendie', 'description' => 'Véhicule incendie', 'status_id' => 1],
            ['code' => 'VBLI', 'label' => 'Véhicule blindé', 'description' => 'Véhicule blindé', 'status_id' => 1],
            ['code' => 'CITE', 'label' => 'Citerne', 'description' => 'Citerne', 'status_id' => 1],
        ];

        Usage::upsert(
            $usages,
            ['code'],
            ['label', 'description', 'status_id']
        );
    }
}
