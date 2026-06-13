<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DataMasterController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\StatistikController;

// 1. Endpoint Public: Login
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');

// 2. Endpoint Protected: Menggunakan Middleware Validasi Token
Route::middleware([\App\Http\Middleware\ValidasiTokenApi::class])->group(function () {
    
    // Auth: Logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // CRUD Data Master (Tabel Entitas)
    Route::get('/master/entitas', [DataMasterController::class, 'index']);          // Get Semua
    Route::post('/master/entitas', [DataMasterController::class, 'store']);         // Tambah Baru
    Route::get('/master/entitas/{id}', [DataMasterController::class, 'show']);      // Get Detail by ID
    Route::put('/master/entitas/{id}', [DataMasterController::class, 'update']);    // Edit by ID
    Route::delete('/master/entitas/{id}', [DataMasterController::class, 'destroy']);// Hapus by ID
    
    // CRUD Data Transaksional
    Route::post('/transaksi/buat', [TransaksiController::class, 'buatTransaksi']);
    Route::get('/transaksi/riwayat/{rekening_id}', [TransaksiController::class, 'riwayat']);
    Route::get('/transaksi/{id}', [TransaksiController::class, 'show']);            // Detail 1 Transaksi
    
    // Statistik Transaksional
    Route::get('/statistik/ringkasan', [StatistikController::class, 'ringkasanSistem']);
});