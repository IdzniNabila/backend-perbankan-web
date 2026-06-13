<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DataMasterController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\StatistikController;

// 1. Endpoint Public: Login
Route::post('/login', [AuthController::class, 'login']);

// 2. Endpoint Protected: Menggunakan Middleware Validasi Token
Route::middleware([\App\Http\Middleware\ValidasiTokenApi::class])->group(function () {
    
    // CRUD Data Master
    Route::get('/master/entitas', [DataMasterController::class, 'index']);
    Route::post('/master/entitas', [DataMasterController::class, 'store']);
    
    // CRUD Data Transaksional
    Route::post('/transaksi/buat', [TransaksiController::class, 'buatTransaksi']);
    Route::get('/transaksi/riwayat/{rekening_id}', [TransaksiController::class, 'riwayat']);
    
    // Statistik Transaksional
    Route::get('/statistik/ringkasan', [StatistikController::class, 'ringkasanSistem']);
});