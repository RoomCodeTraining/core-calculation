<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            StatusSeeder::class,
            EntityTypeSeeder::class,
            EntitySeeder::class,
            RolesAndPermissionsSeeder::class,
            UserSeeder::class,
            BodyworkSeeder::class,
            BrandListSeeder::class,
            ColorSeeder::class,
            VehicleGenreSeeder::class,
            VehicleEnergySeeder::class,
            VehicleAgeSeeder::class,
            VehicleModelListSeeder::class,
            UserActionTypeSeeder::class,
            AppSettingSeeder::class,
            DealerListSeeder::class,
            UsageSeeder::class,
            VehicleGenreUsageSeeder::class,
            VehicleCharacteristicListSeeder::class,
            TransactionTypeSeeder::class,
            ReceiptTypeSeeder::class,
            PaymentMethodSeeder::class,
            PaymentTypeSeeder::class,
            DepreciationTableSeeder::class,
        ]);
    }
}
