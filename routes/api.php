<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\TransactionController;

Route::prefix('v1')->group(function () {
    Route::post('/auth/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
    Route::post('/auth/verify-pin', [AuthController::class, 'verifyPin'])->middleware('throttle:10,1');

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('/auth/logout', [AuthController::class, 'logout']);

        Route::prefix('transfer')->group(function () {
            Route::post('/process', [TransferController::class, 'transfer'])->middleware('throttle:20,1');
            Route::post('/validate', [TransferController::class, 'validateTransfer']);
            Route::post('/quote', [TransferController::class, 'getTransferQuote']);
        });

        Route::prefix('accounts')->group(function () {
            Route::get('/', [AccountController::class, 'index']);
            Route::get('/{account_id}', [AccountController::class, 'show']);
            Route::get('/{account_id}/mutasi', [AccountController::class, 'mutasi']);
            Route::post('/', [AccountController::class, 'store']);
        });

        Route::prefix('transactions')->group(function () {
            Route::get('/', [TransactionController::class, 'index']);
            Route::get('/statistics', [TransactionController::class, 'getStatistics']);
            Route::get('/audit-log', [TransactionController::class, 'getAuditLog']);
            Route::get('/activity-report', [TransactionController::class, 'getActivityReport']);
        });
    });
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return response()->json([
        'status' => 'success',
        'data' => [
            'id' => $request->user()->id,
            'username' => $request->user()->username,
            'email' => $request->user()->email,
            'nama_lengkap' => $request->user()->nama_lengkap,
            'status' => $request->user()->status,
        ],
    ]);
});

Route::get('/health', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'API is running',
        'timestamp' => now()->toIso8601String(),
    ]);
});
