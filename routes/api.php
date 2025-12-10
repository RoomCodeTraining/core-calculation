<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BrandController;
use App\Http\Controllers\API\DepreciationTableController;
use App\Http\Controllers\API\EnergyController;
use App\Http\Controllers\API\GenreController;
use App\Http\Controllers\API\OrganizationController;
use App\Http\Controllers\API\QuotaController;
use App\Http\Controllers\API\UsageController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\VehicleModelController;
use Illuminate\Support\Facades\Route;

// Routes publiques (sans authentification)
Route::post('auth/login', [AuthController::class, 'login']);

// Routes protégées (avec authentification)
Route::middleware('auth:sanctum')->group(function () {
  // Authentification
  Route::post('auth/logout', [AuthController::class, 'logout']);
  Route::get('auth/me', [AuthController::class, 'me']);

  // Organisations
  Route::post('organizations', [OrganizationController::class, 'store']);
  Route::get('organizations', [OrganizationController::class, 'index']);
  Route::get('organizations/{id}', [OrganizationController::class, 'show'])->where('id', '[a-zA-Z0-9_-]+');

  // Quota
  Route::get('quota', [QuotaController::class, 'show']);
  Route::post('quota/recharge', [QuotaController::class, 'recharge']);
  Route::get('quota/recharges', [QuotaController::class, 'recharges']);
  Route::get('quota/usages', [QuotaController::class, 'usages']);

  // Utilisateurs (admins uniquement)
  Route::post('users', [UserController::class, 'store']);
  Route::get('users', [UserController::class, 'index']);
  Route::get('users/{id}', [UserController::class, 'show'])->where('id', '[a-zA-Z0-9_-]+');

  // Autres routes API existantes
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

