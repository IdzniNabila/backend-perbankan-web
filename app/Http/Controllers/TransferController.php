<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rekening;
use App\Models\Mutasi;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransferController extends Controller
{
    public function getQuote(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'data' => [
                'transfer_amount' => (float)$request->amount,
                'fee_amount' => 0.00,
                'total_amount' => (float)$request->amount
            ]
        ]);
    }

    public function transfer(Request $request)
    {
        $request->validate([
            'destination_account_id' => 'required',
            'amount' => 'required|numeric|min:10000',
            'pin' => 'required'
        ]);

        $user = $request->user();
        
        // Cari rekening pengirim
        $source = Rekening::whereHas('nasabah', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->first();

        // Validasi
        if (!$source) return response()->json(['message' => 'Rekening asal tidak ditemukan'], 404);
        if (!Hash::check($request->pin, $source->pin)) return response()->json(['message' => 'PIN Salah!'], 401);
        if ($source->saldo < $request->amount) return response()->json(['message' => 'Saldo tidak cukup'], 422);

        $destination = Rekening::where('nomor_rekening', $request->destination_account_id)->first();
        if (!$destination) return response()->json(['message' => 'Rekening tujuan tidak ditemukan'], 404);

        // Proses Transaksi
        DB::beginTransaction();
        try {
            $source->decrement('saldo', $request->amount);
            $destination->increment('saldo', $request->amount);

            $kodeRef = 'TRX-' . strtoupper(Str::random(8));

            // Log Mutasi Pengirim (Keluar)
            Mutasi::insert([
                'rekening_id' => $source->id,
                'jenis_transaksi' => 'DEBIT',
                'nominal' => $request->amount,
                'keterangan' => $request->description ?? 'Transfer Keluar',
                'kode_referensi' => $kodeRef,
                'waktu_transaksi' => now()
            ]);

            // Log Mutasi Penerima (Masuk)
            Mutasi::insert([
                'rekening_id' => $destination->id,
                'jenis_transaksi' => 'KREDIT',
                'nominal' => $request->amount,
                'keterangan' => 'Terima Transfer',
                'kode_referensi' => $kodeRef,
                'waktu_transaksi' => now()
            ]);

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Transfer Berhasil!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Terjadi kesalahan sistem'], 500);
        }
    }
}