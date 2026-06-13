<?php
use App\Models\ApiUser;

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

        $user = DB::table('pengguna_api')->where('username', $request->username)->first();

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
        DB::table('pengguna_api')->where('id', $user->id)->update([
            'api_token' => $tokenBaru,
            'terakhir_masuk' => now()
        ]);

        return response()->json([
            'status' => 'Sukses',
            'pesan' => 'Autentikasi Berhasil',
            'token' => $tokenBaru
        ], 200);
    }
}