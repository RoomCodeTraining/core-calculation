<?php

use App\Http\Controllers\API\BrandController;
use App\Http\Controllers\API\VehicleModelController;
use App\Http\Controllers\API\VehicleGenreController;
use App\Http\Controllers\API\UsageController;
use App\Http\Controllers\API\VehicleCharacteristicController;
use App\Http\Controllers\API\PriceController;
use App\Http\Controllers\API\DepreciationTableController;
use App\Http\Controllers\API\RechargeController;
use Illuminate\Support\Facades\Route;

Route::name('external.')->group(function () {
    Route::get('/brands', [BrandController::class, 'index'])->name('brands.index');
    Route::get('/vehicle-models', [VehicleModelController::class, 'index'])->name('vehicle-models.index');
    Route::get('/vehicle-genres', [VehicleGenreController::class, 'index'])->name('vehicle-genres.index');
    Route::get('/usages', [UsageController::class, 'index'])->name('usages.index');
    Route::get('/vehicle-characteristics', [VehicleCharacteristicController::class, 'index'])->name('external.vehicle-characteristics.index');
    Route::get('/prices', [PriceController::class, 'index'])->name('prices.index');
    Route::post('/depreciation-tables/calculate-theoretical-market-value', [DepreciationTableController::class, 'calculate_theoretical_market_value_public'])->name('calculate-theoretical-market-value-public');
    Route::get('/vehicle-characteristics/filter/all', [VehicleCharacteristicController::class, 'filterAll'])->name('external.filter-all');
    Route::post('/recharges', [RechargeController::class, 'store'])->name('recharges.store');
}); 