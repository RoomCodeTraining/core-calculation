<?php

namespace Database\Seeders;

use App\Models\Status;
use App\Enums\StatusEnum;
use App\Models\VehicleGenre;
use Illuminate\Database\Seeder;

class VehicleGenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        VehicleGenre::create([
            'code' => 'VP',
            'max_mileage_essence_per_year' => '25000.00',
            'max_mileage_diesel_per_year' => '35000.00',
            'label' => 'Voiture Particulière',
            'description' => 'Voiture Particulière',
            'status_id' => Status::where('code', StatusEnum::ACTIVE)->first()->id,
        ]);

        VehicleGenre::create([
            'code' => 'CTTE',
            'max_mileage_essence_per_year' => '40000.00',
            'max_mileage_diesel_per_year' => '40000.00',
            'label' => 'Camionnette',
            'description' => 'Camionnette',
            'status_id' => Status::where('code', StatusEnum::ACTIVE)->first()->id,
        ]);

        VehicleGenre::create([
            'code' => 'CAM',
            'max_mileage_essence_per_year' => '60000.00',
            'max_mileage_diesel_per_year' => '60000.00',
            'label' => 'Camion',
            'description' => 'Camion',
            'status_id' => Status::where('code', StatusEnum::ACTIVE)->first()->id,
        ]);

        VehicleGenre::create([
            'code' => 'TCP',
            'max_mileage_essence_per_year' => '100000.00',
            'max_mileage_diesel_per_year' => '100000.00',
            'label' => 'Transport en Commun de Personnes',
            'description' => 'Transport en Commun de Personnes',
            'status_id' => Status::where('code', StatusEnum::ACTIVE)->first()->id,
        ]);

        VehicleGenre::create([
            'code' => 'MT',
            'max_mileage_essence_per_year' => '5000.00',
            'max_mileage_diesel_per_year' => '5000.00',
            'label' => 'Motocyclette',
            'description' => 'Motocyclette',
            'status_id' => Status::where('code', StatusEnum::ACTIVE)->first()->id,
        ]);

        VehicleGenre::create([
            'code' => 'REM',
            'max_mileage_essence_per_year' => '0.00',
            'max_mileage_diesel_per_year' => '0.00',
            'label' => 'Remorque',
            'description' => 'Remorque',
            'status_id' => Status::where('code', StatusEnum::ACTIVE)->first()->id,
        ]);

        VehicleGenre::create([
            'code' => 'TRR',
            'max_mileage_essence_per_year' => '80000.00',
            'max_mileage_diesel_per_year' => '80000.00',
            'label' => 'Tracteur Routier',
            'description' => 'Tracteur Routier',
            'status_id' => Status::where('code', StatusEnum::ACTIVE)->first()->id,
        ]);

        VehicleGenre::create([
            'code' => 'ENG',
            'max_mileage_essence_per_year' => '0.00',
            'max_mileage_diesel_per_year' => '0.00',
            'label' => 'Engin Spécial',
            'description' => 'Engin Spécial',
            'status_id' => Status::where('code', StatusEnum::ACTIVE)->first()->id,
        ]);

        VehicleGenre::create([
            'code' => 'VASP',
            'max_mileage_essence_per_year' => '40000.00',
            'max_mileage_diesel_per_year' => '40000.00',
            'label' => 'Véhicule Automoteur Spécialisé',
            'description' => 'Véhicule Automoteur Spécialisé',
            'status_id' => Status::where('code', StatusEnum::ACTIVE)->first()->id,
        ]);

        VehicleGenre::create([
            'code' => 'CIT',
            'max_mileage_essence_per_year' => '40000.00',
            'max_mileage_diesel_per_year' => '40000.00',
            'label' => 'Citerne',
            'description' => 'Citerne',
            'status_id' => Status::where('code', StatusEnum::ACTIVE)->first()->id,
        ]);
        
    }
}
