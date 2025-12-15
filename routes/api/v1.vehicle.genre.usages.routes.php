<?php

use App\Http\Controllers\API\VehicleGenreUsageController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->name('vehicle-genre-usages.')->group(function () {
    Route::get('/', [VehicleGenreUsageController::class, 'index'])->name('index');
    Route::post('/', [VehicleGenreUsageController::class, 'store'])->name('store');
    Route::get('/{vehicle_genre_usage}', [VehicleGenreUsageController::class, 'show'])->name('show');
    Route::put('/{vehicle_genre_usage}', [VehicleGenreUsageController::class, 'update'])->name('update');
    Route::delete('/{vehicle_genre_usage}', [VehicleGenreUsageController::class, 'destroy'])->name('destroy');
}); 