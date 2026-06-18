<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mutasi;
use App\Services\AuditService;
use Illuminate\Http\JsonResponse;

class TransactionController extends Controller
{
    protected AuditService $auditService;

    public function __construct(AuditService $auditService)
    {
        $this->auditService = $auditService;
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $page = (int)$request->input('page', 1);
        $limit = min((int)$request->input('limit', 20), 100);
        $days = min((int)$request->input('days', 30), 365);

        $transactions = Mutasi::whereHas('rekening.nasabah', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })
            ->where('status_proses', 'berhasil')
            ->where('waktu_transaksi', '>=', now()->subDays($days))
            ->orderByDesc('waktu_transaksi')
            ->paginate($limit, ['*'], 'page', $page);

        return response()->json([
            'status' => 'success',
            'data' => $transactions->items(),
            'pagination' => [
                'current_page' => $transactions->currentPage(),
                'per_page' => $transactions->perPage(),
                'total' => $transactions->total(),
                'last_page' => $transactions->lastPage(),
                'has_more' => $transactions->hasMorePages(),
            ],
        ], 200);
    }

    public function getStatistics(Request $request): JsonResponse
    {
        $user = $request->user();
        $days = min((int)$request->input('days', 30), 365);

        $startDate = now()->subDays($days);

        $stats = [
            'total_transfers' => Mutasi::whereHas('rekening.nasabah', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
                ->where('jenis_transaksi', 'DEBIT')
                ->where('waktu_transaksi', '>=', $startDate)
                ->count(),

            'total_received' => Mutasi::whereHas('rekening.nasabah', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
                ->where('jenis_transaksi', 'CREDIT')
                ->where('waktu_transaksi', '>=', $startDate)
                ->count(),

            'total_transferred_amount' => (float)Mutasi::whereHas('rekening.nasabah', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
                ->where('jenis_transaksi', 'DEBIT')
                ->where('waktu_transaksi', '>=', $startDate)
                ->sum('nominal'),

            'total_received_amount' => (float)Mutasi::whereHas('rekening.nasabah', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
                ->where('jenis_transaksi', 'CREDIT')
                ->where('waktu_transaksi', '>=', $startDate)
                ->sum('nominal'),

            'period_days' => $days,
            'period_start' => $startDate->toIso8601String(),
            'period_end' => now()->toIso8601String(),
        ];

        return response()->json([
            'status' => 'success',
            'data' => $stats,
        ], 200);
    }

    public function getAuditLog(Request $request): JsonResponse
    {
        $user = $request->user();
        $days = min((int)$request->input('days', 30), 365);

        $auditLog = $this->auditService->getTransactionHistory(
            user_id: $user->id,
            days: $days,
            limit: 100
        );

        return response()->json([
            'status' => 'success',
            'data' => $auditLog->map(function ($log) {
                return [
                    'id' => $log->id,
                    'action' => $log->action,
                    'tipe_event' => $log->tipe_event,
                    'deskripsi' => $log->deskripsi,
                    'ip_address' => $log->ip_address,
                    'timestamp' => $log->created_at->toIso8601String(),
                    'metadata' => $log->metadata,
                ];
            }),
        ], 200);
    }

    public function getActivityReport(Request $request): JsonResponse
    {
        $user = $request->user();
        $days = min((int)$request->input('days', 30), 365);

        $report = $this->auditService->getUserActivityReport($user->id, $days);

        return response()->json([
            'status' => 'success',
            'data' => $report,
        ], 200);
    }
}
