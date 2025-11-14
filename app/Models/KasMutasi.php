<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KasMutasi extends Model
{
    use HasFactory;

    protected $table = 'kas_mutasis';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'tanggal',
        'shift',
        'jenis',
        'nominal',
        'keterangan',
        'user_iduser',
    ];

    // Relasi ke user (opsional)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_iduser');
    }

    public function scopeRangeTanggal($query, $value1, $value2)
    {
        if ($value1 && $value2) {
            $query->whereBetween('tanggal', [$value1, $value2]);
        }
    }
}
