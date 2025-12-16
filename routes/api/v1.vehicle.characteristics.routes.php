<?php

use App\Http\Controllers\API\VehicleCharacteristicController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->name('vehicle-characteristics.')->group(function () {
    Route::get('/', [VehicleCharacteristicController::class, 'index'])->name('vehicle-characteristics.index');
    Route::post('/', [VehicleCharacteristicController::class, 'store'])->name('vehicle-characteristics.store');
    Route::get('/{vehicle_characteristic}', [VehicleCharacteristicController::class, 'show'])->name('vehicle-characteristics.show');
    Route::put('/{vehicle_characteristic}', [VehicleCharacteristicController::class, 'update'])->name('vehicle-characteristics.update');
    Route::delete('/{vehicle_characteristic}', [VehicleCharacteristicController::class, 'destroy'])->name('vehicle-characteristics.destroy');
    Route::get('/filter-by-vehicle-model/{vehicle_model}', [VehicleCharacteristicController::class, 'filterByVehicleModel'])->name('vehicle-characteristics.filter-by-vehicle-model');
}); 