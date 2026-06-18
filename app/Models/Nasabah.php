<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Nasabah extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'nasabah';

    protected $fillable = [
        'user_id',
        'nama_nasabah',
        'nomor_identitas',
        'jenis_identitas',
        'nomor_telepon',
        'email',
        'alamat',
        'kota',
        'kodepos',
        'status_kepemilikan',
        'tanggal_daftar',
        'status'
    ];

    protected $casts = [
        'tanggal_daftar' => 'date',
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

    public function rekening(): HasMany
    {
        return $this->hasMany(Rekening::class, 'nasabah_id');
    }

    public function rekeningAktif(): HasMany
    {
        return $this->hasMany(Rekening::class, 'nasabah_id')
            ->where('status', 'aktif');
    }
}
