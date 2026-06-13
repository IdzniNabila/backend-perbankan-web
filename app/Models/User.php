<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Arahkan ke tabel sistem autentikasi bank kampus
    protected $table = 'pengguna_api';
    public $timestamps = false;

    // Sesuaikan dengan kolom di database pengguna_api
    protected $fillable = [
        'username',
        'password',
        'api_token',
        'terakhir_masuk',
    ];

    protected $hidden = [
        'password',
        'api_token', // Jika Anda menggunakan bawaan laravel sebelumnya ada remember_token, kita ganti api_token
    ];

    protected function casts(): array
    {
        return [
            'terakhir_masuk' => 'datetime',
            'password' => 'hashed', // Otomatis meng-hash password
        ];
    }
}