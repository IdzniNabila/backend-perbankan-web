<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    use HasUuids;

    protected $table = 'audit_logs';

    protected $fillable = [
        'user_id',
        'action',
        'model_name',
        'model_id',
        'perubahan',
        'ip_address',
        'user_agent',
        'tipe_event',
        'deskripsi',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'array',
        'perubahan' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = true;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeByTipe($query, string $tipe)
    {
        return $query->where('tipe_event', $tipe);
    }

    public function scopeByUser($query, string $user_id)
    {
        return $query->where('user_id', $user_id);
    }

    public function scopeTransfers($query)
    {
        return $query->where('tipe_event', 'TRANSFER');
    }

    public function scopeLogins($query)
    {
        return $query->whereIn('tipe_event', ['LOGIN', 'LOGOUT']);
    }

    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    public static function logTransfer(
        string $user_id,
        string $transfer_id,
        string $deskripsi,
        string $ip_address,
        string $user_agent,
        array $metadata = []
    ): self {
        return static::create([
            'user_id' => $user_id,
            'action' => 'TRANSFER',
            'tipe_event' => 'TRANSFER',
            'model_name' => 'Mutasi',
            'model_id' => $transfer_id,
            'deskripsi' => $deskripsi,
            'ip_address' => $ip_address,
            'user_agent' => $user_agent,
            'metadata' => $metadata,
        ]);
    }

    public static function logLogin(
        string $user_id,
        string $ip_address,
        string $user_agent,
        string $device_name = 'Unknown'
    ): self {
        return static::create([
            'user_id' => $user_id,
            'action' => 'LOGIN',
            'tipe_event' => 'LOGIN',
            'deskripsi' => "User logged in from device: {$device_name}",
            'ip_address' => $ip_address,
            'user_agent' => $user_agent,
            'metadata' => [
                'device_name' => $device_name,
                'timestamp' => now()->toIso8601String(),
            ],
        ]);
    }

    public static function logLogout(string $user_id, string $ip_address): self
    {
        return static::create([
            'user_id' => $user_id,
            'action' => 'LOGOUT',
            'tipe_event' => 'LOGOUT',
            'deskripsi' => 'User logged out',
            'ip_address' => $ip_address,
        ]);
    }

    public static function logSuspiciousActivity(
        string $user_id,
        string $deskripsi,
        string $ip_address,
        array $metadata = []
    ): self {
        return static::create([
            'user_id' => $user_id,
            'action' => 'SUSPICIOUS_ACTIVITY',
            'tipe_event' => 'SUSPICIOUS_ACTIVITY',
            'deskripsi' => $deskripsi,
            'ip_address' => $ip_address,
            'metadata' => $metadata,
        ]);
    }
}
