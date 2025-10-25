<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;

    protected $table = 'penjualans';
    protected $primaryKey = 'idpenjualan';
    public $timestamps = true;

    protected $fillable = [
        'kode_penjualan',
        'customer_name',
        'no_meja',
        'catatan',
        'tanggal',
        'total',
        'bayar',
        'kembalian',
        'user_iduser',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    /**
     * Relasi many-to-one ke model User.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_iduser', 'id');
    }

    public function penjualanDtl()
    {
        return $this->hasMany(PenjualanDtl::class, 'penjualan_idpenjualan', 'idpenjualan')
            ->with('produk'); // optional, eager load produk langsung
    }
    public function detail()
    {
        return $this->hasMany(PenjualanDtl::class, 'penjualan_idpenjualan', 'idpenjualan');
    }

    public function scopeRangeTanggal($query, $value1, $value2)
    {
        if ($value1 && $value2) {
            $query->whereBetween('tanggal', [$value1, $value2]);
        }
    }
}
