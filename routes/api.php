<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Remove the old tripay route
// Route::post('/transaksi', [App\Http\Controllers\TransactionController::class, 'store']);

// Add Midtrans notification callback route
Route::post('/midtrans/notification', [App\Http\Controllers\MidtransController::class, 'notification']);
