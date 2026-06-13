<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class StatistikController extends Controller
{
    // Mengeluarkan output statistik transaksi
    public function ringkasanSistem()
    {
        $statistik = DB::table('transaksi')
            ->join('jenis_transaksi', 'transaksi.jenis_transaksi_id', '=', 'jenis_transaksi.id')
            ->select('jenis_transaksi.kategori', DB::raw('COUNT(transaksi.id) as total_transaksi'), DB::raw('SUM(transaksi.jumlah) as total_perputaran_uang'))
            ->groupBy('jenis_transaksi.kategori')
            ->get();

        return response()->json([
            'status' => 'Sukses',
            'pesan' => 'Statistik Sistem Bank Kampus',
            'data' => $statistik
        ], 200);
    }
}