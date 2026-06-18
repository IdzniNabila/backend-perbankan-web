<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rekening;
use App\Models\Nasabah;
use App\Http\Requests\CreateAccountRequest;
use Illuminate\Http\JsonResponse;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $accounts = Rekening::whereHas('nasabah', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })
            ->with('mutasiTerbaru:10')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $accounts->map(function ($account) {
                return [
                    'id' => $account->id,
                    'nomor_rekening' => $account->nomor_rekening,
                    'jenis_rekening' => $account->jenis_rekening,
                    'saldo' => (float)$account->saldo,
                    'mata_uang' => $account->mata_uang,
                    'status' => $account->status,
                    'tanggal_buka' => $account->tanggal_buka?->toIso8601String(),
                ];
            }),
        ], 200);
    }

    public function show(string $account_id, Request $request): JsonResponse
    {
        $user = $request->user();
        $account = Rekening::whereHas('nasabah', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->find($account_id);

        if (!$account) {
            return response()->json([
                'status' => 'error',
                'message' => 'Account not found',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $account->id,
                'nomor_rekening' => $account->nomor_rekening,
                'jenis_rekening' => $account->jenis_rekening,
                'saldo' => (float)$account->saldo,
                'saldo_minimum' => (float)$account->saldo_minimum,
                'mata_uang' => $account->mata_uang,
                'keterangan' => $account->keterangan,
                'status' => $account->status,
                'tanggal_buka' => $account->tanggal_buka?->toIso8601String(),
                'nasabah' => [
                    'id' => $account->nasabah->id,
                    'nama_nasabah' => $account->nasabah->nama_nasabah,
                    'nomor_identitas' => $account->nasabah->nomor_identitas,
                ],
            ],
        ], 200);
    }

    public function mutasi(string $account_id, Request $request): JsonResponse
    {
        $user = $request->user();
        $account = Rekening::whereHas('nasabah', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->find($account_id);

        if (!$account) {
            return response()->json([
                'status' => 'error',
                'message' => 'Account not found',
            ], 404);
        }

        $page = (int)$request->input('page', 1);
        $limit = min((int)$request->input('limit', 10), 50);

        $mutasi = $account->mutasi()
            ->where('status_proses', 'berhasil')
            ->orderByDesc('waktu_transaksi')
            ->paginate($limit, ['*'], 'page', $page);

        return response()->json([
            'status' => 'success',
            'data' => $mutasi->items(),
            'pagination' => [
                'current_page' => $mutasi->currentPage(),
                'per_page' => $mutasi->perPage(),
                'total' => $mutasi->total(),
                'last_page' => $mutasi->lastPage(),
                'has_more' => $mutasi->hasMorePages(),
            ],
        ], 200);
    }

    public function store(CreateAccountRequest $request): JsonResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        $nasabah = Nasabah::where('user_id', $user->id)
            ->where('id', $validated['nasabah_id'])
            ->first();

        if (!$nasabah) {
            return response()->json([
                'status' => 'error',
                'message' => 'Customer not found or not authorized',
            ], 403);
        }

        try {
            $account = Rekening::create([
                'nasabah_id' => $nasabah->id,
                'nomor_rekening' => $validated['nomor_rekening'],
                'jenis_rekening' => $validated['jenis_rekening'],
                'saldo' => 0,
                'saldo_minimum' => $validated['saldo_minimum'] ?? 0,
                'mata_uang' => 'IDR',
                'keterangan' => $validated['keterangan'] ?? '',
                'status' => 'aktif',
                'tanggal_buka' => now(),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Account created successfully',
                'data' => [
                    'id' => $account->id,
                    'nomor_rekening' => $account->nomor_rekening,
                    'jenis_rekening' => $account->jenis_rekening,
                    'saldo' => (float)$account->saldo,
                    'status' => $account->status,
                ],
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create account: ' . $e->getMessage(),
            ], 500);
        }
    }
}
