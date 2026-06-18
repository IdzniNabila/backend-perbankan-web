<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
        $this->middleware('throttle:5,1')->only('login');
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $result = $this->authService->login(
            username: $request->validated('username'),
            password: $request->validated('password'),
            device_name: $request->validated('device_name', 'Web Browser'),
            ip_address: $request->ip(),
            user_agent: $request->userAgent(),
            device_type: $request->validated('device_type', 'web')
        );

        if (!$result) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid username or password',
            ], 401);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Login successful',
            'data' => $result,
        ], 200);
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            $success = $this->authService->logout(
                user: $request->user(),
                ip_address: $request->ip()
            );

            if ($success) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Logout successful',
                ], 200);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Logout failed',
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Logout failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function verifyPin(Request $request): JsonResponse
    {
        $user = $request->user();
        $pin = $request->input('pin');

        if (!$pin || !preg_match('/^\d{6}$/', $pin)) {
            return response()->json([
                'status' => 'error',
                'message' => 'PIN must be exactly 6 digits',
            ], 422);
        }

        if ($this->authService->verifyPin($user, $pin)) {
            return response()->json([
                'status' => 'success',
                'message' => 'PIN verified',
                'verified' => true,
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Invalid PIN',
            'verified' => false,
        ], 401);
    }
}
