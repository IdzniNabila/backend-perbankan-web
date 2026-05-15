<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;


// DATA MASTER (Tabel Nasabah)

Route::get('/nasabah', function () {
    return response()->json(DB::table('nasabah')->get());
});

// READ 1 (Berdasarkan ID)
Route::get('/nasabah/{id}', function ($id) {
    $nasabah = DB::table('nasabah')->where('id', $id)->first();
    if (!$nasabah) return response()->json(['message' => 'Data tidak ditemukan'], 404);
    return response()->json($nasabah);
});

// INSERT (Create)
Route::post('/nasabah', function (Request $request) {
    DB::table('nasabah')->insert([
        'nik' => $request->nik,
        'nama_lengkap' => $request->nama_lengkap,
        'no_hp' => $request->no_hp
    ]);
    return response()->json(['message' => 'Data Nasabah berhasil ditambah'], 201);
});

// UPDATE
Route::put('/nasabah/{id}', function (Request $request, $id) {
    DB::table('nasabah')->where('id', $id)->update([
        'nama_lengkap' => $request->nama_lengkap,
        'no_hp' => $request->no_hp
    ]);
    return response()->json(['message' => 'Data Nasabah berhasil diupdate']);
});

// DELETE
Route::delete('/nasabah/{id}', function ($id) {
    DB::table('nasabah')->where('id', $id)->delete();
    return response()->json(['message' => 'Data Nasabah berhasil dihapus']);
});


// 2. DATA TRANSAKSIONAL (Tabel Mutasi Transaksi)

Route::get('/mutasi', function () {
    $data = DB::table('mutasi_transaksi')
        ->join('rekening', 'mutasi_transaksi.no_rekening', '=', 'rekening.no_rekening')
        ->join('jenis_transaksi', 'mutasi_transaksi.id_jenis_transaksi', '=', 'jenis_transaksi.id')
        ->select('mutasi_transaksi.*', 'jenis_transaksi.nama_transaksi', 'jenis_transaksi.tipe')
        ->orderBy('mutasi_transaksi.waktu_transaksi', 'desc')
        ->get();
    return response()->json($data);
});

// INSERT Transaksional (Catat Mutasi / Setor Uang) + Validasi
Route::post('/mutasi', function (Request $request) {
    // Validasi: Pastikan rekening ada dan nominal lebih dari 0
    if (!$request->no_rekening || $request->nominal <= 0) {
        return response()->json(['error' => 'Rekening tidak valid atau nominal salah!'], 400);
    }

    DB::table('mutasi_transaksi')->insert([
        'no_rekening' => $request->no_rekening,
        'id_jenis_transaksi' => $request->id_jenis_transaksi,
        'nominal' => $request->nominal
    ]);
    return response()->json(['message' => 'Transaksi berhasil dicatat'], 201);
});

// UPDATE Transaksional
Route::put('/mutasi/{id}', function (Request $request, $id) {
    if ($request->nominal <= 0) {
        return response()->json(['error' => 'Nominal koreksi tidak valid!'], 400);
    }
    DB::table('mutasi_transaksi')->where('id', $id)->update(['nominal' => $request->nominal]);
    return response()->json(['message' => 'Koreksi transaksi berhasil']);
});

// DELETE Transaksional
Route::delete('/mutasi/{id}', function ($id) {
    DB::table('mutasi_transaksi')->where('id', $id)->delete();
    return response()->json(['message' => 'Transaksi dibatalkan dan dihapus']);
});


// STATISTIC DATA TRANSAKSIONAL

Route::get('/statistik', function () {
    // Berapa transaksi dalam bulan ini
    $total_transaksi_bulan_ini = DB::table('mutasi_transaksi')
        ->whereMonth('waktu_transaksi', date('m'))
        ->whereYear('waktu_transaksi', date('Y'))
        ->count();

    // Pelanggan (Nasabah) yang paling BANYAK transaksi (Frekuensi)
    $nasabah_paling_aktif = DB::table('mutasi_transaksi')
        ->join('rekening', 'mutasi_transaksi.no_rekening', '=', 'rekening.no_rekening')
        ->join('nasabah', 'rekening.id_nasabah', '=', 'nasabah.id')
        ->select('nasabah.nama_lengkap', DB::raw('COUNT(mutasi_transaksi.id) as frekuensi_transaksi'))
        ->groupBy('nasabah.id', 'nasabah.nama_lengkap')
        ->orderBy('frekuensi_transaksi', 'desc')
        ->first();

    // Pelanggan dengan nominal transaksi PALING BESAR (Volume Uang)
    $nasabah_transaksi_terbesar = DB::table('mutasi_transaksi')
        ->join('rekening', 'mutasi_transaksi.no_rekening', '=', 'rekening.no_rekening')
        ->join('nasabah', 'rekening.id_nasabah', '=', 'nasabah.id')
        ->select('nasabah.nama_lengkap', DB::raw('SUM(mutasi_transaksi.nominal) as total_putaran_uang'))
        ->groupBy('nasabah.id', 'nasabah.nama_lengkap')
        ->orderBy('total_putaran_uang', 'desc')
        ->first();

    return response()->json([
        'total_transaksi_bulan_ini' => $total_transaksi_bulan_ini,
        'nasabah_paling_sering_transaksi' => $nasabah_paling_aktif,
        'nasabah_putaran_uang_terbesar' => $nasabah_transaksi_terbesar
    ]);
});
