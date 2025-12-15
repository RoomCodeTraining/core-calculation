<?php

use App\Http\Controllers\API\UsageController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->name('usages.')->group(function () {
    Route::get('/', [UsageController::class, 'index'])->name('index');
    Route::post('/', [UsageController::class, 'store'])->name('store');
    Route::get('/{usage}', [UsageController::class, 'show'])->name('show');
    Route::put('/{usage}', [UsageController::class, 'update'])->name('update');
    Route::delete('/{usage}', [UsageController::class, 'destroy'])->name('destroy');
}); 