<?php

use App\Http\Controllers\API\DealerController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->name('dealers.')->group(function () {
    Route::get('/', [DealerController::class, 'index'])->name('index');
    Route::post('/', [DealerController::class, 'store'])->name('store');
    Route::get('/{dealer}', [DealerController::class, 'show'])->name('show');
    Route::put('/{dealer}', [DealerController::class, 'update'])->name('update');
    Route::delete('/{dealer}', [DealerController::class, 'destroy'])->name('destroy');
}); 