<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rekening extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'rekening';

    protected $fillable = [
        'nasabah_id',
        'nomor_rekening',
        'jenis_rekening',
        'saldo',
        'saldo_minimum',
        'mata_uang',
        'keterangan',
        'status',
        'tanggal_buka',
        'tanggal_tutup'
    ];

    protected $casts = [
        'saldo' => 'decimal:2',
        'saldo_minimum' => 'decimal:2',
        'tanggal_buka' => 'datetime',
        'tanggal_tutup' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = true;

    public function nasabah(): BelongsTo
    {
        return $this->belongsTo(Nasabah::class, 'nasabah_id');
    }

    public function mutasi(): HasMany
    {
        return $this->hasMany(Mutasi::class, 'rekening_id');
    }

    public function mutasiDebit(): HasMany
    {
        return $this->hasMany(Mutasi::class, 'rekening_id')
            ->where('jenis_transaksi', 'DEBIT')
            ->orderByDesc('waktu_transaksi');
    }

    public function mutasiCredit(): HasMany
    {
        return $this->hasMany(Mutasi::class, 'rekening_id')
            ->where('jenis_transaksi', 'CREDIT')
            ->orderByDesc('waktu_transaksi');
    }

    public function mutasiTerbaru(int $limit = 10): HasMany
    {
        return $this->hasMany(Mutasi::class, 'rekening_id')
            ->where('status_proses', 'berhasil')
            ->orderByDesc('waktu_transaksi')
            ->limit($limit);
    }

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeByNomor($query, string $nomor_rekening)
    {
        return $query->where('nomor_rekening', $nomor_rekening);
    }

    public function isSaldoCukup(float $nominal): bool
    {
        return (float)$this->saldo >= $nominal;
    }

    public function kredensialValid(): bool
    {
        return $this->status === 'aktif' && !$this->is_deleted;
    }
}
