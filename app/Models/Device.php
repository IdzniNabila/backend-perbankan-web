<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Device extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'devices';

    protected $fillable = [
        'user_id',
        'device_name',
        'user_agent',
        'ip_address',
        'device_type',
        'status',
        'login_pertama_kali',
        'aktivitas_terakhir',
        'logout_time',
        'catatan'
    ];

    protected $casts = [
        'login_pertama_kali' => 'datetime',
        'aktivitas_terakhir' => 'datetime',
        'logout_time' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = true;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeCurigai($query)
    {
        return $query->where('status', 'dicurigai');
    }

    public function scopeByIp($query, string $ip)
    {
        return $query->where('ip_address', $ip);
    }

    public function isAktif(): bool
    {
        return $this->status === 'aktif';
    }

    public function setCurigai(string $catatan = null): void
    {
        $this->status = 'dicurigai';
        if ($catatan) {
            $this->catatan = $catatan;
        }
        $this->save();
    }

    public function setAktif(): void
    {
        $this->status = 'aktif';
        $this->save();
    }
}
