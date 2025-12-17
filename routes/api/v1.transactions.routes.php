<?php

use App\Http\Controllers\API\TransactionController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->name('transactions.')->group(function () {
    Route::get('/', [TransactionController::class, 'index'])->name('transactions.index');
    Route::post('/', [TransactionController::class, 'store'])->name('transactions.store');
    Route::get('/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::put('/{transaction}', [TransactionController::class, 'update'])->name('transactions.update');
    Route::delete('/{transaction}', [TransactionController::class, 'destroy'])->name('transactions.destroy');
    Route::put('/{transaction}/cancel', [TransactionController::class, 'cancel'])->name('transactions.cancel');
    Route::get('/credits/all', [TransactionController::class, 'creditsAll'])->name('transactions.creditsAll');
}); 