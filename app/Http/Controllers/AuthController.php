<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string'
        ]);

        if (!Auth::attempt($request->only('username', 'password'))) {
            return response()->json(['status' => 'error', 'message' => 'Username atau password salah!'], 401);
        }

        $user = User::where('username', $request->username)->firstOrFail();
        $token = $user->createToken('bank_kampus_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Login berhasil',
            'data' => ['access_token' => $token, 'user' => $user]
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['status' => 'success', 'message' => 'Berhasil logout'], 200);
    }
}