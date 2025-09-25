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
        'tanggal',
        'total',
        'bayar',
        'kembalian',
        'user_id',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    /**
     * Relasi many-to-one ke model User.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function penjualanDtl()
    {
        return $this->hasMany(PenjualanDtl::class, 'penjualan_idpenjualan', 'idpenjualan')
            ->with('produk'); // optional, eager load produk langsung
    }
    public function details()
    {
        return $this->hasMany(PenjualanDtl::class, 'penjualan_idpenjualan', 'idpenjualan');
    }
}
