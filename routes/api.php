<?php

use App\Http\Controllers\API\BrandController;
use App\Http\Controllers\API\DepreciationTableController;
use App\Http\Controllers\API\EnergyController;
use App\Http\Controllers\API\GenreController;
use App\Http\Controllers\API\UsageController;
use App\Http\Controllers\API\VehicleModelController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
  Route::get('brands', [BrandController::class, 'index']);
  Route::get('brands/{id}', [BrandController::class, 'show'])->where('id', '[a-zA-Z0-9_-]+');

  Route::get('vehicle-models', [VehicleModelController::class, 'index']);
  Route::get('vehicle-models/{id}', [VehicleModelController::class, 'show'])->where('id', '[a-zA-Z0-9_-]+');

  Route::get('genres', [GenreController::class, 'index']);
  Route::get('genres/{id}', [GenreController::class, 'show'])->where('id', '[a-zA-Z0-9_-]+');

  Route::get('usages', [UsageController::class, 'index']);
  Route::get('usages/{id}', [UsageController::class, 'show'])->where('id', '[a-zA-Z0-9_-]+');

  Route::get('depreciation-tables', [DepreciationTableController::class, 'index']);
  Route::get('depreciation-tables/{id}', [DepreciationTableController::class, 'show'])->where('id', '[a-zA-Z0-9_-]+');
  Route::post('depreciation-tables', [DepreciationTableController::class, 'store'])->middleware('check.api.quota');

  Route::get('energies', [EnergyController::class, 'index']);
});

