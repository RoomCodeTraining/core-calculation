<?php

namespace Database\Seeders;

use App\Models\VehicleGenreUsage;
use App\Models\VehicleGenre;
use App\Models\Usage;
use Illuminate\Database\Seeder;

class VehicleGenreUsageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // VP
        VehicleGenreUsage::create([
            'vehicle_genre_id' => VehicleGenre::where('code', 'VP')->first()->id,
            'usage_id' => Usage::where('code', 'PRIV')->first()->id,
            'max_mileage_essence_per_year' => 25000,
            'max_mileage_diesel_per_year' => 35000,
            'status_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        VehicleGenreUsage::create([
            'vehicle_genre_id' => VehicleGenre::where('code', 'VP')->first()->id,
            'usage_id' => Usage::where('code', 'PROF')->first()->id,
            'max_mileage_essence_per_year' => 25000,
            'max_mileage_diesel_per_year' => 35000,
            'status_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        VehicleGenreUsage::create([
            'vehicle_genre_id' => VehicleGenre::where('code', 'VP')->first()->id,
            'usage_id' => Usage::where('code', 'VTC')->first()->id,
            'max_mileage_essence_per_year' => 80000,
            'max_mileage_diesel_per_year' => 80000,
            'status_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        VehicleGenreUsage::create([
            'vehicle_genre_id' => VehicleGenre::where('code', 'VP')->first()->id,
            'usage_id' => Usage::where('code', 'TAXI')->first()->id,
            'max_mileage_essence_per_year' => 60000,
            'max_mileage_diesel_per_year' => 60000,
            'status_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        VehicleGenreUsage::create([
            'vehicle_genre_id' => VehicleGenre::where('code', 'VP')->first()->id,
            'usage_id' => Usage::where('code', 'AUTO')->first()->id,
            'max_mileage_essence_per_year' => 25000,
            'max_mileage_diesel_per_year' => 35000,
            'status_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        VehicleGenreUsage::create([
            'vehicle_genre_id' => VehicleGenre::where('code', 'VP')->first()->id,
            'usage_id' => Usage::where('code', 'LOCD')->first()->id,
            'max_mileage_essence_per_year' => 25000,
            'max_mileage_diesel_per_year' => 35000,
            'status_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        VehicleGenreUsage::create([
            'vehicle_genre_id' => VehicleGenre::where('code', 'VP')->first()->id,
            'usage_id' => Usage::where('code', 'LOLD')->first()->id,
            'max_mileage_essence_per_year' => 25000,
            'max_mileage_diesel_per_year' => 35000,
            'status_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        VehicleGenreUsage::create([
            'vehicle_genre_id' => VehicleGenre::where('code', 'VP')->first()->id,
            'usage_id' => Usage::where('code', 'SADM')->first()->id,
            'max_mileage_essence_per_year' => 25000,
            'max_mileage_diesel_per_year' => 35000,
            'status_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        // CTTE
        VehicleGenreUsage::create([
            'vehicle_genre_id' => VehicleGenre::where('code', 'CTTE')->first()->id,
            'usage_id' => Usage::where('code', 'PRIV')->first()->id,
            'max_mileage_essence_per_year' => 40000,
            'max_mileage_diesel_per_year' => 40000,
            'status_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        VehicleGenreUsage::create([
            'vehicle_genre_id' => VehicleGenre::where('code', 'CTTE')->first()->id,
            'usage_id' => Usage::where('code', 'TRMA')->first()->id,
            'max_mileage_essence_per_year' => 40000,
            'max_mileage_diesel_per_year' => 40000,
            'status_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        VehicleGenreUsage::create([
            'vehicle_genre_id' => VehicleGenre::where('code', 'CTTE')->first()->id,
            'usage_id' => Usage::where('code', 'LIVR')->first()->id,
            'max_mileage_essence_per_year' => 40000,
            'max_mileage_diesel_per_year' => 40000,
            'status_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        VehicleGenreUsage::create([
            'vehicle_genre_id' => VehicleGenre::where('code', 'CTTE')->first()->id,
            'usage_id' => Usage::where('code', 'CHAN')->first()->id,
            'max_mileage_essence_per_year' => 40000,
            'max_mileage_diesel_per_year' => 40000,
            'status_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        VehicleGenreUsage::create([
            'vehicle_genre_id' => VehicleGenre::where('code', 'CTTE')->first()->id,
            'usage_id' => Usage::where('code', 'STEC')->first()->id,
            'max_mileage_essence_per_year' => 40000,
            'max_mileage_diesel_per_year' => 40000,
            'status_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        VehicleGenreUsage::create([
            'vehicle_genre_id' => VehicleGenre::where('code', 'CTTE')->first()->id,
            'usage_id' => Usage::where('code', 'LOUT')->first()->id,
            'max_mileage_essence_per_year' => 40000,
            'max_mileage_diesel_per_year' => 40000,
            'status_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        VehicleGenreUsage::create([
            'vehicle_genre_id' => VehicleGenre::where('code', 'CTTE')->first()->id,
            'usage_id' => Usage::where('code', 'UTIL')->first()->id,
            'max_mileage_essence_per_year' => 40000,
            'max_mileage_diesel_per_year' => 40000,
            'status_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        // CAM
        VehicleGenreUsage::create([
            'vehicle_genre_id' => VehicleGenre::where('code', 'CAM')->first()->id,
            'usage_id' => Usage::where('code', 'TMAG')->first()->id,
            'max_mileage_essence_per_year' => 60000,
            'max_mileage_diesel_per_year' => 60000,
            'status_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        VehicleGenreUsage::create([
            'vehicle_genre_id' => VehicleGenre::where('code', 'CAM')->first()->id,
            'usage_id' => Usage::where('code', 'TMAT')->first()->id,
            'max_mileage_essence_per_year' => 60000,
            'max_mileage_diesel_per_year' => 60000,
            'status_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        VehicleGenreUsage::create([
            'vehicle_genre_id' => VehicleGenre::where('code', 'CAM')->first()->id,
            'usage_id' => Usage::where('code', 'TFRG')->first()->id,
            'max_mileage_essence_per_year' => 60000,
            'max_mileage_diesel_per_year' => 60000,
            'status_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        VehicleGenreUsage::create([
            'vehicle_genre_id' => VehicleGenre::where('code', 'CAM')->first()->id,
            'usage_id' => Usage::where('code', 'THYC')->first()->id,
            'max_mileage_essence_per_year' => 60000,
            'max_mileage_diesel_per_year' => 60000,
            'status_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        VehicleGenreUsage::create([
            'vehicle_genre_id' => VehicleGenre::where('code', 'CAM')->first()->id,
            'usage_id' => Usage::where('code', 'BTP')->first()->id,
            'max_mileage_essence_per_year' => 60000,
            'max_mileage_diesel_per_year' => 60000,
            'status_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        VehicleGenreUsage::create([
            'vehicle_genre_id' => VehicleGenre::where('code', 'CAM')->first()->id,
            'usage_id' => Usage::where('code', 'LOIN')->first()->id,
            'max_mileage_essence_per_year' => 60000,
            'max_mileage_diesel_per_year' => 60000,
            'status_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        // TCP
        VehicleGenreUsage::create([
            'vehicle_genre_id' => VehicleGenre::where('code', 'TCP')->first()->id,
            'usage_id' => Usage::where('code', 'TURB')->first()->id,
            'max_mileage_essence_per_year' => 100000,
            'max_mileage_diesel_per_year' => 100000,
            'status_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        VehicleGenreUsage::create([
            'vehicle_genre_id' => VehicleGenre::where('code', 'TCP')->first()->id,
            'usage_id' => Usage::where('code', 'TINR')->first()->id,
            'max_mileage_essence_per_year' => 10000,
            'max_mileage_diesel_per_year' => 10000,
            'status_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        VehicleGenreUsage::create([
            'vehicle_genre_id' => VehicleGenre::where('code', 'TCP')->first()->id,
            'usage_id' => Usage::where('code', 'TSCO')->first()->id,
            'max_mileage_essence_per_year' => 10000,
            'max_mileage_diesel_per_year' => 10000,
            'status_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        VehicleGenreUsage::create([
            'vehicle_genre_id' => VehicleGenre::where('code', 'TCP')->first()->id,
            'usage_id' => Usage::where('code', 'TTOU')->first()->id,
            'max_mileage_essence_per_year' => 10000,
            'max_mileage_diesel_per_year' => 10000,
            'status_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        VehicleGenreUsage::create([
            'vehicle_genre_id' => VehicleGenre::where('code', 'TCP')->first()->id,
            'usage_id' => Usage::where('code', 'TENT')->first()->id,
            'max_mileage_essence_per_year' => 10000,
            'max_mileage_diesel_per_year' => 10000,
            'status_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        // MT
        VehicleGenreUsage::create([
            'vehicle_genre_id' => VehicleGenre::where('code', 'MT')->first()->id,
            'usage_id' => Usage::where('code', 'PRIV')->first()->id,
            'max_mileage_essence_per_year' => 5000,
            'max_mileage_diesel_per_year' => 5000,
            'status_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        VehicleGenreUsage::create([
            'vehicle_genre_id' => VehicleGenre::where('code', 'MT')->first()->id,
            'usage_id' => Usage::where('code', 'LIVR')->first()->id,
            'max_mileage_essence_per_year' => 5000,
            'max_mileage_diesel_per_year' => 5000,
            'status_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        VehicleGenreUsage::create([
            'vehicle_genre_id' => VehicleGenre::where('code', 'MT')->first()->id,
            'usage_id' => Usage::where('code', 'TAMO')->first()->id,
            'max_mileage_essence_per_year' => 5000,
            'max_mileage_diesel_per_year' => 5000,
            'status_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        VehicleGenreUsage::create([
            'vehicle_genre_id' => VehicleGenre::where('code', 'MT')->first()->id,
            'usage_id' => Usage::where('code', 'SADM')->first()->id,
            'max_mileage_essence_per_year' => 5000,
            'max_mileage_diesel_per_year' => 5000,
            'status_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        // REM
        VehicleGenreUsage::create([
            'vehicle_genre_id' => VehicleGenre::where('code', 'REM')->first()->id,
            'usage_id' => Usage::where('code', 'TMEL')->first()->id,
            'max_mileage_essence_per_year' => 0,
            'max_mileage_diesel_per_year' => 0,
            'status_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        VehicleGenreUsage::create([
            'vehicle_genre_id' => VehicleGenre::where('code', 'REM')->first()->id,
            'usage_id' => Usage::where('code', 'TRMA')->first()->id,
            'max_mileage_essence_per_year' => 0,
            'max_mileage_diesel_per_year' => 0,
            'status_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        VehicleGenreUsage::create([
            'vehicle_genre_id' => VehicleGenre::where('code', 'REM')->first()->id,
            'usage_id' => Usage::where('code', 'TAGR')->first()->id,
            'max_mileage_essence_per_year' => 0,
            'max_mileage_diesel_per_year' => 0,
            'status_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        VehicleGenreUsage::create([
            'vehicle_genre_id' => VehicleGenre::where('code', 'REM')->first()->id,
            'usage_id' => Usage::where('code', 'TCHA')->first()->id,
            'max_mileage_essence_per_year' => 0,
            'max_mileage_diesel_per_year' => 0,
            'status_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        // TRR
        VehicleGenreUsage::create([
            'vehicle_genre_id' => VehicleGenre::where('code', 'TRR')->first()->id,
            'usage_id' => Usage::where('code', 'TLGD')->first()->id,
            'max_mileage_essence_per_year' => 80000,
            'max_mileage_diesel_per_year' => 80000,
            'status_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        VehicleGenreUsage::create([
            'vehicle_genre_id' => VehicleGenre::where('code', 'TRR')->first()->id,
            'usage_id' => Usage::where('code', 'TINT')->first()->id,
            'max_mileage_essence_per_year' => 80000,
            'max_mileage_diesel_per_year' => 80000,
            'status_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        VehicleGenreUsage::create([
            'vehicle_genre_id' => VehicleGenre::where('code', 'TRR')->first()->id,
            'usage_id' => Usage::where('code', 'TCON')->first()->id,
            'max_mileage_essence_per_year' => 80000,
            'max_mileage_diesel_per_year' => 80000,
            'status_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        // ENG
        VehicleGenreUsage::create([
            'vehicle_genre_id' => VehicleGenre::where('code', 'ENG')->first()->id,
            'usage_id' => Usage::where('code', 'CBTP')->first()->id,
            'max_mileage_essence_per_year' => 0,
            'max_mileage_diesel_per_year' => 0,
            'status_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        VehicleGenreUsage::create([
            'vehicle_genre_id' => VehicleGenre::where('code', 'ENG')->first()->id,
            'usage_id' => Usage::where('code', 'MANU')->first()->id,
            'max_mileage_essence_per_year' => 0,
            'max_mileage_diesel_per_year' => 0,
            'status_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        VehicleGenreUsage::create([
            'vehicle_genre_id' => VehicleGenre::where('code', 'ENG')->first()->id,
            'usage_id' => Usage::where('code', 'EXMI')->first()->id,
            'max_mileage_essence_per_year' => 0,
            'max_mileage_diesel_per_year' => 0,
            'status_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        VehicleGenreUsage::create([
            'vehicle_genre_id' => VehicleGenre::where('code', 'ENG')->first()->id,
            'usage_id' => Usage::where('code', 'TRPU')->first()->id,
            'max_mileage_essence_per_year' => 0,
            'max_mileage_diesel_per_year' => 0,
            'status_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        VehicleGenreUsage::create([
            'vehicle_genre_id' => VehicleGenre::where('code', 'ENG')->first()->id,
            'usage_id' => Usage::where('code', 'AGRI')->first()->id,
            'max_mileage_essence_per_year' => 0,
            'max_mileage_diesel_per_year' => 0,
            'status_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        // VASP
        VehicleGenreUsage::create([
            'vehicle_genre_id' => VehicleGenre::where('code', 'VASP')->first()->id,
            'usage_id' => Usage::where('code', 'AMBU')->first()->id,
            'max_mileage_essence_per_year' => 0,
            'max_mileage_diesel_per_year' => 0,
            'status_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        VehicleGenreUsage::create([
            'vehicle_genre_id' => VehicleGenre::where('code', 'VASP')->first()->id,
            'usage_id' => Usage::where('code', 'DEPN')->first()->id,
            'max_mileage_essence_per_year' => 0,
            'max_mileage_diesel_per_year' => 0,
            'status_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        VehicleGenreUsage::create([
            'vehicle_genre_id' => VehicleGenre::where('code', 'VASP')->first()->id,
            'usage_id' => Usage::where('code', 'CORB')->first()->id,
            'max_mileage_essence_per_year' => 0,
            'max_mileage_diesel_per_year' => 0,
            'status_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        VehicleGenreUsage::create([
            'vehicle_genre_id' => VehicleGenre::where('code', 'VASP')->first()->id,
            'usage_id' => Usage::where('code', 'ATMO')->first()->id,
            'max_mileage_essence_per_year' => 0,
            'max_mileage_diesel_per_year' => 0,
            'status_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        VehicleGenreUsage::create([
            'vehicle_genre_id' => VehicleGenre::where('code', 'VASP')->first()->id,
            'usage_id' => Usage::where('code', 'VINC')->first()->id,
            'max_mileage_essence_per_year' => 0,
            'max_mileage_diesel_per_year' => 0,
            'status_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        VehicleGenreUsage::create([
            'vehicle_genre_id' => VehicleGenre::where('code', 'VASP')->first()->id,
            'usage_id' => Usage::where('code', 'VBLI')->first()->id,
            'max_mileage_essence_per_year' => 0,
            'max_mileage_diesel_per_year' => 0,
            'status_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        // CITE
        VehicleGenreUsage::create([
            'vehicle_genre_id' => VehicleGenre::where('code', 'CITE')->first()->id,
            'usage_id' => Usage::where('code', 'CITE')->first()->id,
            'max_mileage_essence_per_year' => 0,
            'max_mileage_diesel_per_year' => 0,
            'status_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

    }
}
