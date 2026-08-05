<?php

use App\Http\Controllers\API\CalculationController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->name('calculations.')->group(function () {
    Route::get('/', [CalculationController::class, 'index'])->name('index');
    Route::post('/', [CalculationController::class, 'store'])->name('store');
    Route::get('/{calculation}', [CalculationController::class, 'show'])->name('show');
    Route::put('/{calculation}', [CalculationController::class, 'update'])->name('update');
    Route::delete('/{calculation}', [CalculationController::class, 'destroy'])->name('destroy');
}); 