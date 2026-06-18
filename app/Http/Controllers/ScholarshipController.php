<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TransferService;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class ScholarshipController extends Controller
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
     * Klaim dana beasiswa ke rekening milik mahasiswa yang login. Dana
     * selalu berasal dari rekening dana beasiswa (config), dan tujuan wajib
     * rekening milik si pemanggil sendiri (dicek di TransferService::disburse).
     * Sebelumnya halaman frontend "Scholarships" memanggil endpoint
     * /transaksi/buat yang tidak ada di backend dan tanpa verifikasi PIN
     * apapun — siapa pun yang sedang login bisa "mencairkan" nominal
     * berapa pun dari dana beasiswa tanpa kontrol sama sekali.
     */
    public function claim(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'destination_account_id' => ['required', 'uuid', 'exists:rekening,id'],
            'amount' => ['required', 'numeric', 'min:10000'],
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

        try {
            $result = $this->transferService->disburse(
                source_nomor_rekening: config('banking.scholarship_account_number'),
                destination_id: $validated['destination_account_id'],
                amount: (float) $validated['amount'],
                max_amount: (float) config('banking.scholarship_claim_max'),
                description: 'Pencairan Dana Beasiswa',
                user_id: $user->id,
                ip_address: $request->ip(),
                user_agent: $request->userAgent()
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Scholarship funds disbursed',
                'data' => $result,
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Claim failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Claim failed: ' . $e->getMessage(),
            ], 500);
        }
    }
}