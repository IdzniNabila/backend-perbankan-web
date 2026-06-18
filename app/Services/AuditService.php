<?php

namespace App\Services;

use App\Models\AuditLog;

class AuditService
{
    public function logTransfer(
        string $user_id,
        string $transfer_id,
        string $description,
        string $ip_address,
        string $user_agent,
        array $metadata = []
    ): AuditLog {
        return AuditLog::logTransfer(
            $user_id,
            $transfer_id,
            $description,
            $ip_address,
            $user_agent,
            $metadata
        );
    }

    public function logLogin(
        string $user_id,
        string $ip_address,
        string $user_agent,
        string $device_name = 'Unknown'
    ): AuditLog {
        return AuditLog::logLogin($user_id, $ip_address, $user_agent, $device_name);
    }

    public function logLogout(string $user_id, string $ip_address): AuditLog
    {
        return AuditLog::logLogout($user_id, $ip_address);
    }

    public function logSuspiciousActivity(
        string $user_id,
        string $description,
        string $ip_address,
        array $metadata = []
    ): AuditLog {
        return AuditLog::logSuspiciousActivity($user_id, $description, $ip_address, $metadata);
    }

    public function getTransactionHistory(
        string $user_id,
        int $days = 30,
        int $limit = 100
    ): \Illuminate\Database\Eloquent\Collection {
        return AuditLog::where('user_id', $user_id)
            ->where('tipe_event', 'TRANSFER')
            ->where('created_at', '>=', now()->subDays($days))
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    public function getUserActivityReport(string $user_id, int $days = 30): array
    {
        $logs = AuditLog::where('user_id', $user_id)
            ->where('created_at', '>=', now()->subDays($days))
            ->orderByDesc('created_at')
            ->get();

        return [
            'total_events' => $logs->count(),
            'transfers' => $logs->where('tipe_event', 'TRANSFER')->count(),
            'logins' => $logs->where('tipe_event', 'LOGIN')->count(),
            'suspicious_activities' => $logs->where('tipe_event', 'SUSPICIOUS_ACTIVITY')->count(),
            'last_activity' => $logs->first()?->created_at,
            'unique_ips' => $logs->pluck('ip_address')->unique()->count(),
            'period_days' => $days,
        ];
    }

    public function getSystemAuditTrail(int $days = 7, int $limit = 500): \Illuminate\Database\Eloquent\Collection
    {
        return AuditLog::where('created_at', '>=', now()->subDays($days))
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }
}
