<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Créer une organisation de test
        $organization = Organization::create([
            'name' => 'Organisation Admin',
            'slug' => 'organisation-admin',
            'api_quota' => 1000,
        ]);

        // Créer un utilisateur admin
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@core.com',
            'organization_id' => $organization->id,
            'role' => 'admin',
            'api_token' => Str::random(60),
        ]);

        // Créer un utilisateur standard
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'user@core.com',
            'organization_id' => $organization->id,
            'role' => 'user',
            'api_token' => Str::random(60),
        ]);

        // Seed vehicle data in order
        $this->call([
            BrandSeeder::class,
            VehicleModelSeeder::class,
            GenreSeeder::class,
            UsageSeeder::class,
            VehicleAgeSeeder::class,
            DepreciationTableSeeder::class,
            EnergySeeder::class,
        ]);
    }
}
