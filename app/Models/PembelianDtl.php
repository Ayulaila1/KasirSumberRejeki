<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembelianDtl extends Model
{
    use HasFactory;
    protected $table = 'pembeliandtls';
    protected $primaryKey = 'idpembeliandtl';
    public $timestamps = true;
    protected $fillable = ['pembelian_idpembelian', 'produk_idproduk', 'jumlah', 'satuan', 'harga_beli', 'subtotal'];

    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class, 'pembelian_idpembelian', 'idpembelian');
    }
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_idproduk', 'idproduk');
    }
}
