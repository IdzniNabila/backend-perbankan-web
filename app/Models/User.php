<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Authenticatable
{
    use HasApiTokens, HasUuids;

    protected $table = 'pengguna';

    protected $fillable = [
        'username',
        'password',
        'pin_hash',
        'email',
        'nama_lengkap',
        'nomor_identitas',
        'jenis_identitas',
        'nomor_telepon',
        'alamat',
        'status',
        'terakhir_login',
        'ip_terakhir_login',
        'device_terakhir_login'
    ];

    protected $hidden = [
        'password',
        'pin_hash',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'terakhir_login' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = true;

    public function nasabah(): HasOne
    {
        return $this->hasOne(Nasabah::class, 'user_id');
    }

    public function devices(): HasMany
    {
        return $this->hasMany(Device::class, 'user_id');
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class, 'user_id');
    }

    public function mutasi(): HasMany
    {
        return $this->hasMany(Mutasi::class, 'diinisiasi_oleh');
    }

    public function getAuthPassword()
    {
        return $this->password;
    }
}