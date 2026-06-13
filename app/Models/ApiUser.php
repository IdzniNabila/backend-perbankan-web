<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiUser extends Model
{
    // 1. Arahkan ke tabel yang benar 
    protected $table = 'pengguna';

    // 2. Karena tabel tidak menggunakan default timestamps Laravel (created_at & updated_at)
    // melainkan hanya 'terakhir_masuk', kita matikan fiturnya:
    public $timestamps = false;

    // 3. Sesuaikan kolom yang boleh diisi
    protected $fillable = [
        'username',
        'password',
        'api_token',
        'terakhir_masuk',
    ];

    // 4. Sembunyikan data sensitif saat Model dipanggil (misal saat di-return sebagai JSON)
    protected $hidden = [
        'password',
        'api_token',
    ];

    // 5. Ubah format terakhir_masuk menjadi objek waktu (Carbon)
    protected $casts = [
        'terakhir_masuk' => 'datetime',
    ];
}