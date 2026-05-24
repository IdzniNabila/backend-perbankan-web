<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiUser extends Model
{
    protected $table = 'api_users';

    protected $fillable = [
        'username',
        'password',
        'api_token',
        'token_created_at',
    ];

    protected $hidden = [
        'password',
        'api_token',
    ];

    protected $casts = [
        'token_created_at' => 'datetime',
    ];
}
