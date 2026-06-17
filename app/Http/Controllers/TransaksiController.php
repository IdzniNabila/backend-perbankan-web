<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    // POST /transaksi/buat — Catat Transaksi Baru
    public function buatTransaksi(Request $request)
    {
        $request->validate([
            'rekening_id'        => 'required|integer|exists:rekening,id',
            'jenis_transaksi_id' => 'required|integer|exists:jenis_transaksi,id',
            'jumlah'             => 'required|numeric|min:500',
        ]);

        DB::beginTransaction();
        try {
            $jenis   = DB::table('jenis_transaksi')->where('id', $request->jenis_transaksi_id)->first();
            $kodeRef = 'TRX-' . strtoupper(uniqid());

            // Insert ke tabel transaksi
            $transaksiId = DB::table('transaksi')->insertGetId([
                'rekening_id'        => $request->rekening_id,
                'jenis_transaksi_id' => $request->jenis_transaksi_id,
                'jumlah'             => $request->jumlah,
                'kode_referensi'     => $kodeRef,
                'tanggal_transaksi'  => now(),
            ]);

            // FIX: Cek saldo sebelum debit agar tidak minus
            if ($jenis->kategori === 'DEBIT') {
                $rekening = DB::table('rekening')->where('id', $request->rekening_id)->first();
                if ($rekening->saldo < $request->jumlah) {
                    DB::rollBack();
                    return response()->json([
                        'status' => 'Gagal',
                        'pesan'  => 'Saldo tidak mencukupi untuk transaksi ini.'
                    ], 422);
                }
                DB::table('rekening')->where('id', $request->rekening_id)->decrement('saldo', $request->jumlah);
            } else {
                DB::table('rekening')->where('id', $request->rekening_id)->increment('saldo', $request->jumlah);
            }

            DB::commit();

            // Ambil detail transaksi lengkap
            $detail = DB::table('transaksi')
                ->join('rekening',         'transaksi.rekening_id',        '=', 'rekening.id')
                ->join('entitas',          'rekening.entitas_id',          '=', 'entitas.id')
                ->join('jenis_transaksi',  'transaksi.jenis_transaksi_id', '=', 'jenis_transaksi.id')
                ->select(
                    'transaksi.id as transaksi_id',
                    'transaksi.kode_referensi',
                    'entitas.nama_entitas',
                    'rekening.nomor_rekening',
                    'jenis_transaksi.nama_jenis as jenis_transaksi',
                    'jenis_transaksi.kategori',
                    'transaksi.jumlah',
                    'transaksi.tanggal_transaksi'
                )
                ->where('transaksi.id', $transaksiId)
                ->first();

            return response()->json([
                'status' => 'Sukses',
                'pesan'  => 'Transaksi berhasil dicatat',
                'data'   => $detail
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'Gagal',
                'pesan'  => 'Transaksi dibatalkan. ' . $e->getMessage()
            ], 500);
        }
    }

    // GET /transaksi/riwayat/{rekening_id} — Riwayat Transaksi (paginasi)
    public function riwayat($rekening_id)
    {
        // FIX: Tambahkan paginate agar Transactions.jsx bisa baca last_page
        $riwayat = DB::table('transaksi')
            ->join('rekening',        'transaksi.rekening_id',        '=', 'rekening.id')
            ->join('entitas',         'rekening.entitas_id',          '=', 'entitas.id')
            ->join('jenis_transaksi', 'transaksi.jenis_transaksi_id', '=', 'jenis_transaksi.id')
            ->select(
                'transaksi.id as transaksi_id',
                'transaksi.kode_referensi',
                'entitas.nama_entitas',
                'rekening.nomor_rekening',
                'jenis_transaksi.nama_jenis as jenis_transaksi',
                'jenis_transaksi.kategori',
                'transaksi.jumlah',
                'transaksi.tanggal_transaksi'
            )
            ->where('transaksi.rekening_id', $rekening_id)
            ->orderByDesc('transaksi.tanggal_transaksi')
            ->paginate(10);

        return response()->json([
            'status' => 'Sukses',
            'data'   => $riwayat
        ], 200);
    }

    // GET /transaksi/{id} — Detail 1 Transaksi
    public function show($id)
    {
        $transaksi = DB::table('transaksi')
            ->join('rekening',        'transaksi.rekening_id',        '=', 'rekening.id')
            ->join('entitas',         'rekening.entitas_id',          '=', 'entitas.id')
            ->join('jenis_transaksi', 'transaksi.jenis_transaksi_id', '=', 'jenis_transaksi.id')
            ->select(
                'transaksi.id as transaksi_id',
                'transaksi.kode_referensi',
                'entitas.nama_entitas',
                'rekening.nomor_rekening',
                'jenis_transaksi.nama_jenis as jenis_transaksi',
                'jenis_transaksi.kategori',
                'transaksi.jumlah',
                'transaksi.tanggal_transaksi'
            )
            ->where('transaksi.id', $id)
            ->first();

        if (!$transaksi) {
            return response()->json(['status' => 'Gagal', 'pesan' => 'Transaksi tidak ditemukan'], 404);
        }

        return response()->json(['status' => 'Sukses', 'data' => $transaksi], 200);
    }
}
