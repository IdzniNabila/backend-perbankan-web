<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mutasi extends Model
{
    protected $table = 'mutasi';
    public $timestamps = false;
    protected $casts = [
        'waktu_transaksi' => 'datetime',
        'nominal' => 'float'
    ];

    public function rekening()
    {
        return $this->belongsTo(Rekening::class, 'rekening_id', 'id');
    }
}