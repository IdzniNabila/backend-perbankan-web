<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mutasi extends Model
{
    use HasUuids;

    protected $table = 'mutasi';

    protected $fillable = [
        'rekening_id',
        'referensi_transaksi_id',
        'jenis_transaksi',
        'keterangan',
        'nominal',
        'saldo_sebelum',
        'saldo_setelah',
        'referensi_eksternal',
        'diinisiasi_oleh',
        'ip_address',
        'device_info',
        'status_proses',
        'alasan_gagal',
        'waktu_transaksi'
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
        'saldo_sebelum' => 'decimal:2',
        'saldo_setelah' => 'decimal:2',
        'waktu_transaksi' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = true;

    public function rekening(): BelongsTo
    {
        return $this->belongsTo(Rekening::class, 'rekening_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diinisiasi_oleh');
    }

    public function scopeDebit($query)
    {
        return $query->where('jenis_transaksi', 'DEBIT');
    }

    public function scopeCredit($query)
    {
        return $query->where('jenis_transaksi', 'CREDIT');
    }

    public function scopeBerhasil($query)
    {
        return $query->where('status_proses', 'berhasil');
    }

    public function scopeByReferensi($query, string $referensi)
    {
        return $query->where('referensi_transaksi_id', $referensi)
            ->orWhere('referensi_eksternal', $referensi);
    }

    public function scopeByNominal($query, float $nominal)
    {
        return $query->where('nominal', $nominal);
    }

    public function isDebit(): bool
    {
        return $this->jenis_transaksi === 'DEBIT';
    }

    public function isCredit(): bool
    {
        return $this->jenis_transaksi === 'CREDIT';
    }

    public function isBerhasil(): bool
    {
        return $this->status_proses === 'berhasil';
    }

    public function saldoValid(): bool
    {
        return $this->saldo_sebelum >= 0 && $this->saldo_setelah >= 0;
    }
}
