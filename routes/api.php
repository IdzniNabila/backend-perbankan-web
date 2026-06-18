<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TransferController;

// Rute Publik (Login)
Route::post('/login', [AuthController::class, 'login']);

// Rute Terproteksi (Harus Login)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    Route::get('/dashboard/stats', [DashboardController::class, 'getStats']);
    
    Route::get('/transactions', [TransactionController::class, 'index']);
    
    Route::get('/transfer/quote', [TransferController::class, 'getQuote']);
    Route::post('/transfer', [TransferController::class, 'transfer']);
});