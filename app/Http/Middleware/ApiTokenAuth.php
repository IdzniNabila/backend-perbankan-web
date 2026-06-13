<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ValidasiTokenApi
{
    public function handle(Request $request, Closure $next)
    {
        // Ambil token dari Header: Authorization: Bearer <token>
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['status' => 'Gagal', 'pesan' => 'Token autentikasi tidak ditemukan!'], 401);
        }

        // Cek token ke tabel pengguna_api
        $user = DB::table('pengguna_api')->where('api_token', $token)->first();

        if (!$user) {
            return response()->json(['status' => 'Gagal', 'pesan' => 'Token salah atau sudah tidak berlaku!'], 401);
        }

        // Jika lolos, teruskan request ke endpoint tujuan
        return $next($request);
    }
}