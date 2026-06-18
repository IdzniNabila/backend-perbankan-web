<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rekening;
use App\Services\TransferService;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    protected TransferService $transferService;
    protected AuthService $authService;

    public function __construct(TransferService $transferService, AuthService $authService)
    {
        $this->transferService = $transferService;
        $this->authService = $authService;
        $this->middleware('auth:sanctum');
        $this->middleware('throttle:20,1');
    }

    /**
     * Bayar UKT: transfer dari rekening milik mahasiswa yang login ke
     * rekening keuangan kampus (nomor rekening diambil dari config, tidak
     * pernah dikirim oleh client). Sebelumnya halaman frontend "Payments"
     * memanggil endpoint /transaksi/buat yang tidak pernah ada di backend
     * dan tidak meminta PIN sama sekali.
     */
    public function payTuition(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'source_account_id' => ['required', 'uuid', 'exists:rekening,id'],
            'amount' => ['required', 'numeric', 'min:10000', 'max:999999999'],
            'pin' => ['required', 'digits:6'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();
        $user = $request->user();

        if (!$this->authService->verifyPin($user, $validated['pin'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid PIN',
                'errors' => ['pin' => ['PIN salah']],
            ], 422);
        }

        $financeAccount = Rekening::where('nomor_rekening', config('banking.finance_account_number'))
            ->where('status', 'aktif')
            ->first();

        if (!$financeAccount) {
            return response()->json([
                'status' => 'error',
                'message' => 'Campus finance account is not configured. Please contact the administrator.',
            ], 500);
        }

        try {
            $result = $this->transferService->transfer(
                source_id: $validated['source_account_id'],
                destination_id: $financeAccount->id,
                amount: (float) $validated['amount'],
                description: 'Pembayaran UKT',
                user_id: $user->id,
                ip_address: $request->ip(),
                user_agent: $request->userAgent(),
                pin_verified: 1
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Tuition payment successful',
                'data' => $result,
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Payment failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Payment failed: ' . $e->getMessage(),
            ], 500);
        }
    }
}