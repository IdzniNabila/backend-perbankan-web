<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TransferService;
use App\Services\ValidationService;
use App\Http\Requests\TransferRequest;
use Illuminate\Http\JsonResponse;

class TransferController extends Controller
{
    protected TransferService $transferService;
    protected ValidationService $validationService;

    public function __construct(
        TransferService $transferService,
        ValidationService $validationService
    ) {
        $this->transferService = $transferService;
        $this->validationService = $validationService;
        $this->middleware('auth:sanctum');
        $this->middleware('throttle:20,1');
    }

    public function transfer(TransferRequest $request): JsonResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        $pin_verified = $this->validationService->validatePin($validated['pin']);
        if (!$pin_verified['is_valid']) {
            return response()->json([
                'status' => 'error',
                'message' => 'PIN validation failed',
                'errors' => $pin_verified['errors'],
            ], 422);
        }

        try {
            $result = $this->transferService->transfer(
                source_id: $validated['source_account_id'],
                destination_id: $validated['destination_account_id'],
                amount: $validated['amount'],
                description: $validated['description'] ?? 'Transfer between accounts',
                user_id: $user->id,
                ip_address: $request->ip(),
                user_agent: $request->userAgent(),
                pin_verified: 1
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Transfer successful',
                'data' => $result,
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Transfer failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Transfer failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function validateTransfer(Request $request): JsonResponse
    {
        $validation = $this->validationService->validateTransferRequest(
            source_account_id: $request->input('source_account_id'),
            destination_account_id: $request->input('destination_account_id'),
            amount: (float)$request->input('amount', 0),
            pin: $request->input('pin', '')
        );

        return response()->json([
            'status' => $validation['is_valid'] ? 'success' : 'error',
            'valid' => $validation['is_valid'],
            'errors' => $validation['errors'],
        ], $validation['is_valid'] ? 200 : 422);
    }

    public function getTransferQuote(Request $request): JsonResponse
    {
        $amount = (float)$request->input('amount', 0);
        $fee = $amount * 0.01;
        $total = $amount + $fee;

        return response()->json([
            'status' => 'success',
            'data' => [
                'transfer_amount' => $amount,
                'fee_rate' => 0.01,
                'fee_amount' => $fee,
                'total_amount' => $total,
                'currency' => 'IDR',
            ],
        ], 200);
    }
}
