<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

Route::post('/login', [ApiController::class, 'login']);

Route::middleware(\App\Http\Middleware\ApiTokenAuth::class)
    ->controller(ApiController::class)
    ->group(function () {
    // NASABAH
    Route::get('/nasabah', 'getNasabah');
    Route::get('/nasabah/{id}', 'getNasabahById');
    Route::post('/nasabah', 'createNasabah');
    Route::put('/nasabah/{id}', 'updateNasabah');
    Route::delete('/nasabah/{id}', 'deleteNasabah');

    // REKENING
    Route::get('/rekening', 'getRekening');
    Route::get('/rekening/{no_rekening}', 'getRekeningByNoRekening');
    Route::post('/rekening', 'createRekening');
    Route::put('/rekening/{no_rekening}', 'updateRekening');
    Route::delete('/rekening/{no_rekening}', 'deleteRekening');

    // MUTASI
    Route::get('/mutasi', 'getMutasi');
    Route::get('/mutasi/{id}', 'getMutasiById');
    Route::get('/mutasi/rekening/{no_rekening}', 'getMutasiByRekening');
    Route::post('/mutasi', 'createMutasi');
    Route::put('/mutasi/{id}', 'updateMutasi');
    Route::delete('/mutasi/{id}', 'deleteMutasi');

    // STATISTIK
    Route::get('/statistik', 'getStatistik');
});
