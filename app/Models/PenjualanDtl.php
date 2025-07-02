<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanDtl extends Model
{
    use HasFactory;

    // Catatan: Nama tabel `penjualandtls` memiliki primary key `idpenjualan`.
    // Ini mungkin sebuah kesalahan desain pada skema. Idealnya, nama primary key adalah `idpenjualandtl`.
    // Untuk saat ini, kita ikuti skema yang ada.
    protected $table = 'penjualandtls';
    protected $primaryKey = 'idpenjualan';
    public $timestamps = true;

    protected $fillable = [
        'penjualan_idpenjualan',
        'produk_idproduk',
        'qty',
        'harga_jual',
        'subtotal',
    ];

    /**
     * Relasi many-to-one ke model Penjualan.
     */
    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'penjualan_idpenjualan', 'idpenjualan');
    }

    /**
     * Relasi many-to-one ke model Produk.
     */
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_idproduk', 'idproduk');
    }
}
