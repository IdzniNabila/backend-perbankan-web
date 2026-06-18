<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nasabah extends Model
{
    protected $table = 'nasabah';
    public $timestamps = false;

    public function rekening()
    {
        return $this->hasMany(Rekening::class, 'nasabah_id', 'id');
    }
}