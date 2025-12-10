<?php

use App\Http\Services\MarketValueService;
use App\Models\DepreciationTable;
use App\Models\Energy;
use App\Models\Genre;
use App\Models\Usage;
use App\Models\VehicleAge;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->service = new MarketValueService();
});

test('calcule la valeur théorique du marché pour un véhicule de moins de 60 mois', function () {
    // Arrange
    $genre = Genre::factory()->create([
        'max_mileage_essence_per_year' => 5000,
        'max_mileage_diesel_per_year' => 5000,
    ]);

    $usage = Usage::factory()->create([
        'genre_id' => $genre->id,
        'max_mileage_essence_per_year' => 5000,
        'max_mileage_diesel_per_year' => 5000,
    ]);

    $energy = Energy::factory()->create([
        'code' => 'VE01',
    ]);

    $vehicleAge = VehicleAge::factory()->create([
        'value' => 24, // 24 mois
    ]);

    DepreciationTable::factory()->create([
        'usage_id' => $genre->id, // usage_id pointe vers genres
        'vehicle_age_id' => $vehicleAge->id,
        'value' => 15.5,
    ]);

    $firstEntryDate = now()->subMonths(24)->format('Y-m-d');
    $expertiseDate = now()->format('Y-m-d');

    // Act
    $result = $this->service->calculateTheoreticalMarketValue(
        $usage->id,
        $energy->id,
        20000,
        10000,
        $firstEntryDate,
        $expertiseDate
    );

    // Assert
    expect($result)->toBeArray()
        ->and($result['month_diff'])->toBe(24.0)
        ->and($result['vehicle_age'])->toBe(24)
        ->and($result['theorical_depreciation_rate'])->toBe(15.5)
        ->and($result['usage'])->toBeInstanceOf(Usage::class)
        ->and($result['energy'])->toBeInstanceOf(Energy::class);
});

test('calcule la valeur théorique du marché pour un véhicule entre 60 et 84 mois', function () {
    // Arrange
    $genre = Genre::factory()->create([
        'max_mileage_essence_per_year' => 5000,
        'max_mileage_diesel_per_year' => 5000,
    ]);

    $usage = Usage::factory()->create([
        'genre_id' => $genre->id,
        'max_mileage_essence_per_year' => 5000,
        'max_mileage_diesel_per_year' => 5000,
    ]);

    $energy = Energy::factory()->create([
        'code' => 'VE02',
    ]);

    $vehicleAge = VehicleAge::factory()->create([
        'value' => 65, // 65 mois
    ]);

    $vehicleAgeMin = VehicleAge::factory()->create([
        'value' => 60,
    ]);

    $vehicleAgeMax = VehicleAge::factory()->create([
        'value' => 66,
    ]);

    DepreciationTable::factory()->create([
        'usage_id' => $genre->id,
        'vehicle_age_id' => $vehicleAgeMin->id,
        'value' => 40.0,
    ]);

    DepreciationTable::factory()->create([
        'usage_id' => $genre->id,
        'vehicle_age_id' => $vehicleAgeMax->id,
        'value' => 45.0,
    ]);

    $firstEntryDate = now()->subMonths(65)->format('Y-m-d');
    $expertiseDate = now()->format('Y-m-d');

    // Act
    $result = $this->service->calculateTheoreticalMarketValue(
        $usage->id,
        $energy->id,
        20000,
        10000,
        $firstEntryDate,
        $expertiseDate
    );

    // Assert
    expect($result)->toBeArray()
        ->and($result['month_diff'])->toBe(65.0)
        ->and($result['vehicle_age'])->toBe(65)
        ->and($result['theorical_depreciation_rate'])->toBeGreaterThan(40.0)
        ->and($result['theorical_depreciation_rate'])->toBeLessThan(45.0);
});

test('calcule la valeur théorique du marché pour un véhicule entre 84 et 120 mois', function () {
    // Arrange
    $genre = Genre::factory()->create([
        'max_mileage_essence_per_year' => 5000,
        'max_mileage_diesel_per_year' => 5000,
    ]);

    $usage = Usage::factory()->create([
        'genre_id' => $genre->id,
        'max_mileage_essence_per_year' => 5000,
        'max_mileage_diesel_per_year' => 5000,
    ]);

    $energy = Energy::factory()->create([
        'code' => 'VE01',
    ]);

    $vehicleAge = VehicleAge::factory()->create([
        'value' => 90, // 90 mois
    ]);

    $vehicleAgeMin = VehicleAge::factory()->create([
        'value' => 84,
    ]);

    $vehicleAgeMax = VehicleAge::factory()->create([
        'value' => 96,
    ]);

    DepreciationTable::factory()->create([
        'usage_id' => $genre->id,
        'vehicle_age_id' => $vehicleAgeMin->id,
        'value' => 60.0,
    ]);

    DepreciationTable::factory()->create([
        'usage_id' => $genre->id,
        'vehicle_age_id' => $vehicleAgeMax->id,
        'value' => 70.0,
    ]);

    $firstEntryDate = now()->subMonths(90)->format('Y-m-d');
    $expertiseDate = now()->format('Y-m-d');

    // Act
    $result = $this->service->calculateTheoreticalMarketValue(
        $usage->id,
        $energy->id,
        20000,
        10000,
        $firstEntryDate,
        $expertiseDate
    );

    // Assert
    expect($result)->toBeArray()
        ->and($result['month_diff'])->toBe(90.0)
        ->and($result['vehicle_age'])->toBe(90)
        ->and($result['theorical_depreciation_rate'])->toBeGreaterThan(60.0)
        ->and($result['theorical_depreciation_rate'])->toBeLessThan(70.0);
});

test('retourne des valeurs par défaut quand le véhicule age n\'existe pas', function () {
    // Arrange
    $genre = Genre::factory()->create();
    $usage = Usage::factory()->create(['genre_id' => $genre->id]);
    $energy = Energy::factory()->create();

    $firstEntryDate = now()->subMonths(200)->format('Y-m-d');
    $expertiseDate = now()->format('Y-m-d');

    // Act
    $result = $this->service->calculateTheoreticalMarketValue(
        $usage->id,
        $energy->id,
        20000,
        10000,
        $firstEntryDate,
        $expertiseDate
    );

    // Assert
    expect($result)->toBeArray()
        ->and($result['vehicle_age'])->toBe(0)
        ->and($result['theorical_depreciation_rate'])->toBe(0)
        ->and($result['theorical_vehicle_market_value'])->toBe(0);
});

test('calcule correctement la valeur théorique du marché', function () {
    // Arrange
    $genre = Genre::factory()->create([
        'max_mileage_essence_per_year' => 5000,
        'max_mileage_diesel_per_year' => 5000,
    ]);

    $usage = Usage::factory()->create([
        'genre_id' => $genre->id,
        'max_mileage_essence_per_year' => 5000,
        'max_mileage_diesel_per_year' => 5000,
    ]);

    $energy = Energy::factory()->create([
        'code' => 'VE01',
    ]);

    $vehicleAge = VehicleAge::factory()->create([
        'value' => 12, // 12 mois
    ]);

    DepreciationTable::factory()->create([
        'usage_id' => $genre->id,
        'vehicle_age_id' => $vehicleAge->id,
        'value' => 10.0,
    ]);

    $firstEntryDate = now()->subMonths(12)->format('Y-m-d');
    $expertiseDate = now()->format('Y-m-d');
    $vehicleNewValue = 20000;

    // Act
    $result = $this->service->calculateTheoreticalMarketValue(
        $usage->id,
        $energy->id,
        $vehicleNewValue,
        10000,
        $firstEntryDate,
        $expertiseDate
    );

    // Assert
    $expectedMarketValue = ceil($vehicleNewValue - ($vehicleNewValue * 10.0 / 100));
    expect($result['theorical_vehicle_market_value'])->toBe($expectedMarketValue);
});
