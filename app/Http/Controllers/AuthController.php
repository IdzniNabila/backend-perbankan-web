<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        // FIX: Gunakan tabel 'pengguna' (sesuai database SQL yang ada)
        $user = DB::table('pengguna')->where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'Gagal',
                'pesan'  => 'Username atau Password salah!'
            ], 401);
        }

        // Generate token baru (60 karakter)
        $tokenBaru = Str::random(60);

        // Update token di database
        DB::table('pengguna')->where('id', $user->id)->update([
            'api_token'     => $tokenBaru,
            'terakhir_masuk' => now()
        ]);

        return response()->json([
            'status' => 'Sukses',
            'pesan'  => 'Autentikasi Berhasil',
            'token'  => $tokenBaru
        ], 200);
    }

    public function logout(Request $request)
    {
        $token = $request->bearerToken();

        // FIX: Gunakan tabel 'pengguna' (konsisten)
        DB::table('pengguna')
            ->where('api_token', $token)
            ->update(['api_token' => null]);

        return response()->json([
            'status' => 'Sukses',
            'pesan'  => 'Berhasil logout, token telah dinonaktifkan.'
        ], 200);
    }
}
