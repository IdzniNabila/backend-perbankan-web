<?php

namespace App\Services;

use App\Models\User;
use App\Models\Device;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function login(
        string $username,
        string $password,
        string $device_name,
        string $ip_address,
        string $user_agent,
        string $device_type = 'web'
    ): ?array {
        $user = User::where('username', $username)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return null;
        }

        if ($user->status !== 'aktif') {
            return null;
        }

        try {
            $token = $user->createToken($device_name)->plainTextToken;

            $device = $this->trackDevice(
                $user->id,
                $device_name,
                $ip_address,
                $user_agent,
                $device_type
            );

            $user->update([
                'terakhir_login' => now(),
                'ip_terakhir_login' => $ip_address,
                'device_terakhir_login' => $device_name,
            ]);

            AuditLog::logLogin($user->id, $ip_address, $user_agent, $device_name);

            return [
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'nama_lengkap' => $user->nama_lengkap,
                ],
                'device_id' => $device->id,
                'expires_in' => 604800,
            ];
        } catch (\Exception $e) {
            return null;
        }
    }

    public function logout(User $user, string $ip_address): bool
    {
        try {
            $user->currentAccessToken()->delete();
            AuditLog::logLogout($user->id, $ip_address);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function verifyPin(User $user, string $pin): bool
    {
        return Hash::check($pin, $user->pin_hash);
    }

    private function trackDevice(
        string $user_id,
        string $device_name,
        string $ip_address,
        string $user_agent,
        string $device_type = 'web'
    ): Device {
        $device = Device::where('user_id', $user_id)
            ->where('ip_address', $ip_address)
            ->where('device_type', $device_type)
            ->first();

        if ($device) {
            $device->update([
                'device_name' => $device_name,
                'user_agent' => $user_agent,
                'aktivitas_terakhir' => now(),
                'status' => 'aktif',
            ]);
        } else {
            $device = Device::create([
                'user_id' => $user_id,
                'device_name' => $device_name,
                'user_agent' => $user_agent,
                'ip_address' => $ip_address,
                'device_type' => $device_type,
                'status' => 'aktif',
                'login_pertama_kali' => now(),
                'aktivitas_terakhir' => now(),
            ]);

            AuditLog::logSuspiciousActivity(
                $user_id,
                "New device detected: {$device_name} from IP {$ip_address}",
                $ip_address,
                ['device_name' => $device_name, 'device_type' => $device_type]
            );
        }

        return $device;
    }
}
