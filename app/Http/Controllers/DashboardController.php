<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mutasi;

class DashboardController extends Controller
{
    public function getStats(Request $request)
    {
        $user = $request->user();
        
        $stats = Mutasi::whereHas('rekening.nasabah', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->where('status_proses', 'berhasil')
            ->selectRaw("
                COUNT(CASE WHEN jenis_transaksi = 'DEBIT' THEN 1 END) as transfers_count,
                COUNT(CASE WHEN jenis_transaksi = 'KREDIT' THEN 1 END) as received_count,
                SUM(CASE WHEN jenis_transaksi = 'DEBIT' THEN nominal ELSE 0 END) as total_out,
                SUM(CASE WHEN jenis_transaksi = 'KREDIT' THEN nominal ELSE 0 END) as total_in
            ")->first();

        return response()->json([
            'status' => 'success',
            'data' => [
                'total_transfers' => (int)$stats->transfers_count,
                'total_received' => (int)$stats->received_count,
                'total_transferred_amount' => (float)$stats->total_out,
                'total_received_amount' => (float)$stats->total_in,
            ]
        ]);
    }
}