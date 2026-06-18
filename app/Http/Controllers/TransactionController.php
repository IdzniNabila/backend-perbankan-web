<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mutasi;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $limit = min((int)$request->input('limit', 20), 100);

        $transactions = Mutasi::whereHas('rekening.nasabah', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->where('status_proses', 'berhasil')
            ->orderByDesc('waktu_transaksi')
            ->paginate($limit);

        return response()->json([
            'status' => 'success',
            'data' => $transactions->items(),
            'pagination' => ['last_page' => $transactions->lastPage()]
        ]);
    }
}