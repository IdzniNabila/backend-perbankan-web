<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/login",
     *     tags={"Autentikasi"},
     *     summary="Login pengguna bank kampus",
     *     description="Gunakan username dan password untuk mendapatkan Bearer Token",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"username","password"},
     *             @OA\Property(property="username", type="string", example="admin_pusat"),
     *             @OA\Property(property="password", type="string", example="rahasia123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil login",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="Sukses"),
     *             @OA\Property(property="pesan", type="string", example="Autentikasi Berhasil"),
     *             @OA\Property(property="token", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Username atau password salah",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="Gagal"),
     *             @OA\Property(property="pesan", type="string", example="Username atau Password salah!")
     *         )
     *     )
     * )
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $user = DB::table('pengguna')->where('username', $request->username)->first();

        // Validasi Kredensial
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'Gagal',
                'pesan' => 'Username atau Password salah!'
            ], 401);
        }

        // Generate Random Token (60 Karakter)
        $tokenBaru = Str::random(60);

        // Update token di database
        DB::table('pengguna')->where('id', $user->id)->update([
            'api_token' => $tokenBaru,
            'terakhir_masuk' => now()
        ]);

        return response()->json([
            'status' => 'Sukses',
            'pesan' => 'Autentikasi Berhasil',
            'token' => $tokenBaru
        ], 200);
    }

    /**
     * @OA\Post(
     * path="/api/logout",
     * tags={"Autentikasi"},
     * summary="Logout akun pengguna",
     * description="Menonaktifkan token yang sedang berjalan",
     * security={{"bearerAuth":{}}},
     * @OA\Response(response=200, description="Berhasil logout")
     * )
     */
    public function logout(Request $request)
    {
        $token = $request->bearerToken();

        DB::table('pengguna')
            ->where('api_token', $token)
            ->update(['api_token' => null]);

        return response()->json([
            'status' => 'Sukses',
            'pesan' => 'Berhasil logout, token telah dinonaktifkan.'
        ], 200);
    }
}