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
            $jenis = DB::table('jenis_transaksi')->where('id', $request->jenis_transaksi_id)->first();
            
            $kodeRef = uniqid('TRX-');

            // Insert ke riwayat transaksi
            $transaksiId = DB::table('transaksi')->insertGetId([
                'rekening_id' => $request->rekening_id,
                'jenis_transaksi_id' => $request->jenis_transaksi_id,
                'jumlah' => $request->jumlah,
                'kode_referensi' => $kodeRef,
                'tanggal_transaksi' => now()
            ]);

            // Update Saldo Rekening
            if ($jenis->kategori === 'DEBIT') {
                DB::table('rekening')->where('id', $request->rekening_id)->decrement('saldo', $request->jumlah);
            } else {
                DB::table('rekening')->where('id', $request->rekening_id)->increment('saldo', $request->jumlah);
            }

            DB::commit();

            // Mengambil detail transaksi yang baru dibuat beserta data relasinya
            $detailTransaksi = DB::table('transaksi')
                ->join('rekening', 'transaksi.rekening_id', '=', 'rekening.id')
                ->join('entitas', 'rekening.entitas_id', '=', 'entitas.id')
                ->join('jenis_transaksi', 'transaksi.jenis_transaksi_id', '=', 'jenis_transaksi.id')
                ->select(
                    'transaksi.kode_referensi',
                    'entitas.nama_entitas',
                    'rekening.nomor_rekening',
                    'jenis_transaksi.nama_jenis as jenis_transaksi',
                    'transaksi.jumlah',
                    'transaksi.tanggal_transaksi'
                )
                ->where('transaksi.id', $transaksiId)
                ->first();

            return response()->json([
                'status' => 'Sukses', 
                'pesan' => 'Transaksi berhasil dicatat',
                'data' => $detailTransaksi
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'Gagal', 'pesan' => 'Transaksi dibatalkan. ' . $e->getMessage()], 500);
        }
    }

    // Read Data Transaksional: Riwayat Berdasarkan Rekening (Menampilkan Data Lengkap)
    public function riwayat($rekening_id)
    {
        $riwayat = DB::table('transaksi')
            ->join('rekening', 'transaksi.rekening_id', '=', 'rekening.id')
            ->join('entitas', 'rekening.entitas_id', '=', 'entitas.id')
            ->join('jenis_transaksi', 'transaksi.jenis_transaksi_id', '=', 'jenis_transaksi.id')
            ->select(
                'transaksi.id as transaksi_id',
                'transaksi.tanggal_transaksi',
                'transaksi.kode_referensi',
                'entitas.nama_entitas',
                'rekening.nomor_rekening',
                'jenis_transaksi.nama_jenis as jenis_transaksi',
                'jenis_transaksi.kategori',
                'transaksi.jumlah'
            )
            ->where('transaksi.rekening_id', $rekening_id)
            ->orderBy('transaksi.tanggal_transaksi', 'desc')
            ->get();

        return response()->json(['status' => 'Sukses', 'data' => $riwayat], 200);
    }

    // Membaca Detail Satu Transaksi Berdasarkan ID (Primary Key)
    public function show($id)
    {
        $transaksi = DB::table('transaksi')
            ->join('rekening', 'transaksi.rekening_id', '=', 'rekening.id')
            ->join('entitas', 'rekening.entitas_id', '=', 'entitas.id')
            ->join('jenis_transaksi', 'transaksi.jenis_transaksi_id', '=', 'jenis_transaksi.id')
            ->select(
                'transaksi.id as transaksi_id',
                'transaksi.tanggal_transaksi',
                'transaksi.kode_referensi',
                'entitas.nama_entitas',
                'entitas.kategori as kategori_nasabah',
                'rekening.nomor_rekening',
                'jenis_transaksi.nama_jenis as jenis_transaksi',
                'jenis_transaksi.kategori as mutasi',
                'transaksi.jumlah'
            )

            ->where('transaksi.id', $id)
            ->orderBy('transaksi.tanggal_transaksi', 'desc')
            ->paginate(10);

        if (!$transaksi) {
            return response()->json(['status' => 'Gagal', 'pesan' => 'Data transaksi tidak ditemukan'], 404);
        }

        return response()->json(['status' => 'Sukses', 'data' => $transaksi], 200);
    }
}