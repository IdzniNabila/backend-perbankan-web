<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

Route::controller(ApiController::class)->group(function () {
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

    // JENIS TRANSAKSI
    Route::get('/jenis-transaksi', 'getJenisTransaksi');
    Route::get('/jenis-transaksi/{id}', 'getJenisTransaksiById');
    Route::post('/jenis-transaksi', 'createJenisTransaksi');
    Route::put('/jenis-transaksi/{id}', 'updateJenisTransaksi');
    Route::delete('/jenis-transaksi/{id}', 'deleteJenisTransaksi');

    // CABANG BANK
    Route::get('/cabang-bank', 'getCabangBank');
    Route::get('/cabang-bank/{id}', 'getCabangBankById');
    Route::post('/cabang-bank', 'createCabangBank');
    Route::put('/cabang-bank/{id}', 'updateCabangBank');
    Route::delete('/cabang-bank/{id}', 'deleteCabangBank');

    // JENIS REKENING
    Route::get('/jenis-rekening', 'getJenisRekening');
    Route::get('/jenis-rekening/{id}', 'getJenisRekeningById');
    Route::post('/jenis-rekening', 'createJenisRekening');
    Route::put('/jenis-rekening/{id}', 'updateJenisRekening');
    Route::delete('/jenis-rekening/{id}', 'deleteJenisRekening');

    // TRANSAKSI PEMBAYARAN
    Route::get('/transaksi-pembayaran', 'getTransaksiPembayaran');
    Route::get('/transaksi-pembayaran/{id}', 'getTransaksiPembayaranById');
    Route::post('/transaksi-pembayaran', 'createTransaksiPembayaran');
    Route::put('/transaksi-pembayaran/{id}', 'updateTransaksiPembayaran');
    Route::delete('/transaksi-pembayaran/{id}', 'deleteTransaksiPembayaran');

    // DETAIL PEMBAYARAN
    Route::get('/detail-pembayaran', 'getDetailPembayaran');
    Route::get('/detail-pembayaran/{id}', 'getDetailPembayaranById');
    Route::post('/detail-pembayaran', 'createDetailPembayaran');
    Route::put('/detail-pembayaran/{id}', 'updateDetailPembayaran');
    Route::delete('/detail-pembayaran/{id}', 'deleteDetailPembayaran');

    // MUTASI
    Route::get('/mutasi', 'getMutasi');
    Route::get('/mutasi/{id}', 'getMutasiById');
    Route::get('/mutasi/rekening/{no_rekening}', 'getMutasiByRekening');
    Route::post('/mutasi', 'createMutasi');
    Route::put('/mutasi/{id}', 'updateMutasi');
    Route::delete('/mutasi/{id}', 'deleteMutasi');

    // STATISTIK
    Route::get('/statistik', 'getStatistik');
    Route::post('/statistik', 'createStatistik');
    Route::put('/statistik/{id}', 'updateStatistik');
    Route::delete('/statistik/{id}', 'deleteStatistik');
});
