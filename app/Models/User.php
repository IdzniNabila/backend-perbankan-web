<?php
namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens;
    protected $table = 'users';
    protected $fillable = ['username', 'password'];
    protected $hidden = ['password'];
    protected $casts = [
        'password' => 'hashed'
    ];

    public function nasabah()
    {
        return $this->hasOne(Nasabah::class, 'user_id', 'id');
    }
}