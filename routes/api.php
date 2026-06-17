<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DataMasterController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\StatistikController;

// 1. Endpoint Public: Login (throttle 5 request per menit)
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');

// 2. Endpoint Protected: Menggunakan Middleware Validasi Token
Route::middleware([\App\Http\Middleware\ValidasiTokenApi::class])->group(function () {

    // Auth: Logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // CRUD Data Master (Tabel Entitas)
    Route::get('/master/entitas',           [DataMasterController::class, 'index']);
    Route::post('/master/entitas',          [DataMasterController::class, 'store']);
    Route::get('/master/entitas/{id}',      [DataMasterController::class, 'show']);
    Route::put('/master/entitas/{id}',      [DataMasterController::class, 'update']);
    Route::delete('/master/entitas/{id}',   [DataMasterController::class, 'destroy']);

    // CRUD Data Transaksional
    Route::post('/transaksi/buat',                          [TransaksiController::class, 'buatTransaksi']);
    Route::get('/transaksi/riwayat/{rekening_id}',          [TransaksiController::class, 'riwayat']);
    Route::get('/transaksi/{id}',                           [TransaksiController::class, 'show']);

    // Statistik
    Route::get('/statistik/ringkasan', [StatistikController::class, 'ringkasanSistem']);
});
