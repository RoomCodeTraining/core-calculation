<?php

use App\Http\Controllers\API\PriceController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->name('prices.')->group(function () {
    Route::get('/', [PriceController::class, 'index'])->name('index');
    Route::post('/', [PriceController::class, 'store'])->name('store');
    Route::get('/{price}', [PriceController::class, 'show'])->name('show');
    Route::put('/{price}', [PriceController::class, 'update'])->name('update');
    Route::delete('/{price}', [PriceController::class, 'destroy'])->name('destroy');
}); 