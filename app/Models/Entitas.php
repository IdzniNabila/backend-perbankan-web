namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entitas extends Model
{
    protected $table = 'entitas';
    public $timestamps = false;

    // Relasi ke User login
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // Relasi ke Rekening
    public function rekening()
    {
        return $this->hasMany(Rekening::class, 'entitas_id', 'id');
    }
}