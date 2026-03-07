<?php

use App\Http\Controllers\API\OrderController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->name('orders.')->group(function () {
    Route::get('/', [OrderController::class, 'index'])->name('index');
    Route::post('/', [OrderController::class, 'store'])->name('store');
    Route::get('/{order}', [OrderController::class, 'show'])->name('show');
    Route::put('/{order}', [OrderController::class, 'update'])->name('update');
    Route::put('/{order}/validate', [OrderController::class, 'validate'])->name('validate');
    Route::put('/{order}/cancel', [OrderController::class, 'cancel'])->name('cancel');
    Route::put('/{order}/reject', [OrderController::class, 'reject'])->name('reject');
}); 