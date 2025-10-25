<?php

namespace App\Models;

use App\Models\User;
use App\Models\Bahan;
use App\Models\Pembeliandtl;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pengeluaran extends Model
{
    use HasFactory;

    protected $table = 'pengeluarans';
    protected $primaryKey = 'idpengeluaran';
    public $timestamps = true;

    protected $fillable = [
        'idpengeluaran',
        'bahan_idbahan',
        'tanggal',
        'shift',
        'jumlah',
        'harga',
        'total',
        'keterangan',
        'user_iduser',
        'created_at',
        'updated_at',
    ];

    public function scopeSearch($query, $value)
    {
        $query->where('idpengeluaran', 'like', "%{$value}%")
            ->orWhere('bahan_idbahan', 'like', "%{$value}%")
            ->orWhere('tanggal', 'like', "%{$value}%")
            ->orWhere('shift', 'like', "%{$value}%")
            ->orWhere('jumlah', 'like', "%{$value}%")
            ->orWhere('harga', 'like', "%{$value}%")
            ->orWhere('total', 'like', "%{$value}%")
            ->orWhere('keterangan', 'like', "%{$value}%")
            ->orWhere('user_iduser', 'like', "%{$value}%")
            ->orWhere('created_at', 'like', "%{$value}%")
            ->orWhere('updated_at', 'like', "%{$value}%")
        ;
    }

    protected $casts = [
        'tanggal' => 'date',
    ];

    /**
     * Relasi many-to-one ke model User.
     */

    public function bahan()
    {
        return $this->belongsTo(Bahan::class, 'bahan_idbahan', 'idbahan');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_iduser', 'id');
    }

    public function pembeliandtl()
    {
        return $this->hasOne(Pembeliandtl::class, 'bahan_idbahan', 'idbahan');
    }


    public function scopeRangeTanggal($query, $value1, $value2)
    {
        if ($value1 && $value2) {
            $query->whereBetween('tanggal', [$value1, $value2]);
        }
    }
}
