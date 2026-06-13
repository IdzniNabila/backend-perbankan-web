<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    // Create Data Transaksional
    public function buatTransaksi(Request $request)
    {
        $request->validate([
            'rekening_id' => 'required|integer',
            'jenis_transaksi_id' => 'required|integer',
            'jumlah' => 'required|numeric|min:500'
        ]);

        DB::beginTransaction();
        try {
            // 1. Cari kategori transaksi (DEBIT/KREDIT)
            $jenis = DB::table('jenis_transaksi')->where('id', $request->jenis_transaksi_id)->first();
            
            // 2. Insert ke riwayat transaksi
            DB::table('transaksi')->insert([
                'rekening_id' => $request->rekening_id,
                'jenis_transaksi_id' => $request->jenis_transaksi_id,
                'jumlah' => $request->jumlah,
                'kode_referensi' => uniqid('TRX-')
            ]);

            // 3. Update Saldo Rekening
            if ($jenis->kategori === 'DEBIT') {
                DB::table('rekening')->where('id', $request->rekening_id)->decrement('saldo', $request->jumlah);
            } else {
                DB::table('rekening')->where('id', $request->rekening_id)->increment('saldo', $request->jumlah);
            }

            DB::commit();
            return response()->json(['status' => 'Sukses', 'pesan' => 'Transaksi berhasil dicatat'], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'Gagal', 'pesan' => 'Transaksi dibatalkan. ' . $e->getMessage()], 500);
        }
    }

    // Read Data Transaksional
    public function riwayat($rekening_id)
    {
        $riwayat = DB::table('transaksi')->where('rekening_id', $rekening_id)->get();
        return response()->json(['status' => 'Sukses', 'data' => $riwayat], 200);
    }
}