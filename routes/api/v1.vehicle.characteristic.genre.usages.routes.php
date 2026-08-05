<?php

use App\Http\Controllers\API\VehicleCharacteristicGenreUsageController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->name('vehicle-characteristic-genre-usages.')->group(function () {
    Route::get('/', [VehicleCharacteristicGenreUsageController::class, 'index'])->name('index');
    Route::post('/', [VehicleCharacteristicGenreUsageController::class, 'store'])->name('store');
    Route::get('/{characteristic_genre_usage}', [VehicleCharacteristicGenreUsageController::class, 'show'])->name('show');
    Route::put('/{characteristic_genre_usage}', [VehicleCharacteristicGenreUsageController::class, 'update'])->name('update');
    Route::delete('/{characteristic_genre_usage}', [VehicleCharacteristicGenreUsageController::class, 'destroy'])->name('destroy');
});
