<?php

use App\Http\Controllers\API\TransactionTypeController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->name('transaction-types.')->group(function () {
    Route::get('/', [TransactionTypeController::class, 'index'])->name('transaction-types.index');
    Route::post('/', [TransactionTypeController::class, 'store'])->name('transaction-types.store');
    Route::get('/{transaction_type}', [TransactionTypeController::class, 'show'])->name('transaction-types.show');
    Route::put('/{transaction_type}', [TransactionTypeController::class, 'update'])->name('transaction-types.update');
    Route::delete('/{transaction_type}', [TransactionTypeController::class, 'destroy'])->name('transaction-types.destroy');
    Route::put('/{transaction_type}/enable', [TransactionTypeController::class, 'enable'])->name('transaction-types.enable');
    Route::put('/{transaction_type}/disable', [TransactionTypeController::class, 'disable'])->name('transaction-types.disable');
}); 