<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiTokenAuth
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken() ?: $request->header('X-API-TOKEN') ?: $request->query('api_token');
        $token = $token ? trim($token) : null;

        if ($token && str_starts_with($token, 'Bearer ')) {
            $token = substr($token, 7);
        }

        $token = $token ? trim($token, " \t\n\r\0\x0B\"'") : null;

        if (!$token) {
            return response()->json(['message' => 'Token autentikasi diperlukan'], 401);
        }

        $user = DB::connection('mysql')->table('api_users')->where('api_token', $token)->first();
        if (!$user) {
            return response()->json(['message' => 'Token tidak valid atau kadaluarsa'], 401);
        }

        return $next($request);
    }
}
