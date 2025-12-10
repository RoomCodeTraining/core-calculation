<?php

use App\Models\DepreciationTable;
use App\Models\Energy;
use App\Models\Genre;
use App\Models\Usage;
use App\Models\VehicleAge;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('calcule la dépréciation avec succès pour un véhicule essence', function () {
    // Arrange
    $genre = Genre::factory()->create([
        'max_mileage_essence_per_year' => 12000,
        'max_mileage_diesel_per_year' => 15000,
    ]);

    $usage = Usage::factory()->create([
        'genre_id' => $genre->id,
        'max_mileage_essence_per_year' => 12000,
        'max_mileage_diesel_per_year' => 15000,
    ]);

    $energy = Energy::factory()->create([
        'code' => 'VE01',
    ]);

    $vehicleAge = VehicleAge::factory()->create([
        'value' => 24,
    ]);

    DepreciationTable::factory()->create([
        'usage_id' => $genre->id, // usage_id pointe vers genres
        'vehicle_age_id' => $vehicleAge->id,
        'value' => 15.5,
    ]);

    $firstEntryDate = now()->subMonths(24)->format('Y-m-d');
    $expertiseDate = now()->format('Y-m-d');

    $payload = [
        'usage_id' => $usage->id,
        'energy_id' => $energy->id,
        'vehicle_new_value' => 20000,
        'vehicle_mileage' => 20000,
        'first_entry_into_circulation_date' => $firstEntryDate,
        'expertise_date' => $expertiseDate,
        'market_incidence_rate' => 5,
    ];

    // Act
    $response = $this->postJson('/api/depreciation-tables', $payload);

    // Assert
    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'expertise_date',
                'first_entry_into_circulation_date',
                'vehicle_new_value',
                'year_diff',
                'month_diff',
                'vehicle_age',
                'theorical_depreciation_rate',
                'theorical_vehicle_market_value',
                'is_up',
                'market_incidence_rate',
                'market_incidence',
                'kilometric_incidence',
                'depreciation_rate',
                'vehicle_market_value',
            ],
        ]);

    $data = $response->json('data');
    expect($data['vehicle_new_value'])->toBe(20000)
        ->and($data['month_diff'])->toBeIn([24, 24.0])
        ->and($data['theorical_depreciation_rate'])->toBe(15.5)
        ->and($data['market_incidence_rate'])->toBe(5);
});

test('calcule la dépréciation avec succès pour un véhicule diesel', function () {
    // Arrange
    $genre = Genre::factory()->create([
        'max_mileage_essence_per_year' => 12000,
        'max_mileage_diesel_per_year' => 15000,
    ]);

    $usage = Usage::factory()->create([
        'genre_id' => $genre->id,
        'max_mileage_essence_per_year' => 12000,
        'max_mileage_diesel_per_year' => 15000,
    ]);

    $energy = Energy::factory()->create([
        'code' => 'VE02',
    ]);

    $vehicleAge = VehicleAge::factory()->create([
        'value' => 36,
    ]);

    DepreciationTable::factory()->create([
        'usage_id' => $genre->id,
        'vehicle_age_id' => $vehicleAge->id,
        'value' => 25.0,
    ]);

    $firstEntryDate = now()->subMonths(36)->format('Y-m-d');
    $expertiseDate = now()->format('Y-m-d');

    $payload = [
        'usage_id' => $usage->id,
        'energy_id' => $energy->id,
        'vehicle_new_value' => 30000,
        'vehicle_mileage' => 40000,
        'first_entry_into_circulation_date' => $firstEntryDate,
        'expertise_date' => $expertiseDate,
    ];

    // Act
    $response = $this->postJson('/api/depreciation-tables', $payload);

    // Assert
    $response->assertStatus(200);

    $data = $response->json('data');
    expect($data['vehicle_new_value'])->toBe(30000)
        ->and($data['month_diff'])->toBeIn([36, 36.0])
        ->and($data['theorical_depreciation_rate'])->toBeIn([25, 25.0]);
});

test('valide les champs requis', function () {
    // Act
    $response = $this->postJson('/api/depreciation-tables', []);

    // Assert
    $response->assertStatus(422);

    $errors = $response->json('errors');
    expect($errors)->toBeArray()
        ->and(collect($errors)->pluck('source.pointer'))->toContain('/usage_id', '/energy_id', '/vehicle_new_value', '/vehicle_mileage', '/first_entry_into_circulation_date', '/expertise_date');
});

test('valide que usage_id existe', function () {
    $energy = Energy::factory()->create();

    $payload = [
        'usage_id' => 99999,
        'energy_id' => $energy->id,
        'vehicle_new_value' => 20000,
        'vehicle_mileage' => 10000,
        'first_entry_into_circulation_date' => now()->subMonths(12)->format('Y-m-d'),
        'expertise_date' => now()->format('Y-m-d'),
    ];

    $response = $this->postJson('/api/depreciation-tables', $payload);

    $response->assertStatus(422);

    $errors = $response->json('errors');
    expect(collect($errors)->pluck('source.pointer'))->toContain('/usage_id');
});

test('valide que energy_id existe', function () {
    $genre = Genre::factory()->create();
    $usage = Usage::factory()->create(['genre_id' => $genre->id]);

    $payload = [
        'usage_id' => $usage->id,
        'energy_id' => 99999,
        'vehicle_new_value' => 20000,
        'vehicle_mileage' => 10000,
        'first_entry_into_circulation_date' => now()->subMonths(12)->format('Y-m-d'),
        'expertise_date' => now()->format('Y-m-d'),
    ];

    $response = $this->postJson('/api/depreciation-tables', $payload);

    $response->assertStatus(422);

    $errors = $response->json('errors');
    expect(collect($errors)->pluck('source.pointer'))->toContain('/energy_id');
});

test('calcule correctement l\'incidence kilométrique positive (is_up = true)', function () {
    // Arrange
    $genre = Genre::factory()->create([
        'max_mileage_essence_per_year' => 12000, // 1000 km/mois
        'max_mileage_diesel_per_year' => 15000,
    ]);

    $usage = Usage::factory()->create([
        'genre_id' => $genre->id,
        'max_mileage_essence_per_year' => 12000,
        'max_mileage_diesel_per_year' => 15000,
    ]);

    $energy = Energy::factory()->create([
        'code' => 'VE01',
    ]);

    $vehicleAge = VehicleAge::factory()->create([
        'value' => 24,
    ]);

    DepreciationTable::factory()->create([
        'usage_id' => $genre->id,
        'vehicle_age_id' => $vehicleAge->id,
        'value' => 15.0,
    ]);

    // Véhicule avec moins de kilomètres que prévu (bonus)
    $payload = [
        'usage_id' => $usage->id,
        'energy_id' => $energy->id,
        'vehicle_new_value' => 20000,
        'vehicle_mileage' => 15000, // Moins que 24 * 1000 = 24000
        'first_entry_into_circulation_date' => now()->subMonths(24)->format('Y-m-d'),
        'expertise_date' => now()->format('Y-m-d'),
    ];

    // Act
    $response = $this->postJson('/api/depreciation-tables', $payload);

    // Assert
    $response->assertStatus(200);
    $data = $response->json('data');
    expect($data['is_up'])->toBeTrue()
        ->and($data['kilometric_incidence'])->toBeGreaterThan(0);
});

test('calcule correctement l\'incidence kilométrique négative (is_up = false)', function () {
    // Arrange
    $genre = Genre::factory()->create([
        'max_mileage_essence_per_year' => 12000, // 1000 km/mois
        'max_mileage_diesel_per_year' => 15000,
    ]);

    $usage = Usage::factory()->create([
        'genre_id' => $genre->id,
        'max_mileage_essence_per_year' => 12000,
        'max_mileage_diesel_per_year' => 15000,
    ]);

    $energy = Energy::factory()->create([
        'code' => 'VE01',
    ]);

    $vehicleAge = VehicleAge::factory()->create([
        'value' => 24,
    ]);

    DepreciationTable::factory()->create([
        'usage_id' => $genre->id,
        'vehicle_age_id' => $vehicleAge->id,
        'value' => 15.0,
    ]);

    // Véhicule avec plus de kilomètres que prévu (malus)
    $payload = [
        'usage_id' => $usage->id,
        'energy_id' => $energy->id,
        'vehicle_new_value' => 20000,
        'vehicle_mileage' => 30000, // Plus que 24 * 1000 = 24000
        'first_entry_into_circulation_date' => now()->subMonths(24)->format('Y-m-d'),
        'expertise_date' => now()->format('Y-m-d'),
    ];

    // Act
    $response = $this->postJson('/api/depreciation-tables', $payload);

    // Assert
    $response->assertStatus(200);
    $data = $response->json('data');
    expect($data['is_up'])->toBeFalse()
        ->and($data['kilometric_incidence'])->toBeLessThan(0);
});

test('limite l\'incidence kilométrique à la moitié de la valeur théorique si elle dépasse', function () {
    // Arrange
    $genre = Genre::factory()->create([
        'max_mileage_essence_per_year' => 12000,
        'max_mileage_diesel_per_year' => 15000,
    ]);

    $usage = Usage::factory()->create([
        'genre_id' => $genre->id,
        'max_mileage_essence_per_year' => 12000,
        'max_mileage_diesel_per_year' => 15000,
    ]);

    $energy = Energy::factory()->create([
        'code' => 'VE01',
    ]);

    $vehicleAge = VehicleAge::factory()->create([
        'value' => 12,
    ]);

    DepreciationTable::factory()->create([
        'usage_id' => $genre->id,
        'vehicle_age_id' => $vehicleAge->id,
        'value' => 10.0,
    ]);

    $payload = [
        'usage_id' => $usage->id,
        'energy_id' => $energy->id,
        'vehicle_new_value' => 20000,
        'vehicle_mileage' => 0, // Très peu de kilomètres = très grande incidence positive
        'first_entry_into_circulation_date' => now()->subMonths(12)->format('Y-m-d'),
        'expertise_date' => now()->format('Y-m-d'),
    ];

    // Act
    $response = $this->postJson('/api/depreciation-tables', $payload);

    // Assert
    $response->assertStatus(200);
    $data = $response->json('data');
    $maxKilometricIncidence = $data['theorical_vehicle_market_value'] / 2;
    expect($data['kilometric_incidence'])->toBeLessThanOrEqual($maxKilometricIncidence);
});

