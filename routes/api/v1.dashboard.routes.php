<?php

use App\Http\Controllers\API\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->name('dashboard.')->group(function () {
    Route::get('/users', [DashboardController::class, 'users'])->name('users');
    Route::get('/calculations', [DashboardController::class, 'calculations'])->name('calculations');
    Route::get('/vehicle-characteristics', [DashboardController::class, 'vehicleCharacteristics'])->name('vehicleCharacteristics');
});
