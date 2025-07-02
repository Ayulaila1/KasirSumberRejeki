<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdukRacikan extends Model
{
    use HasFactory;
    protected $table = 'produk_racikans';
    protected $primaryKey = 'idproduk_racikan';
    public $timestamps = true;
    protected $fillable = ['produk_idproduk', 'bahan_idbahan', 'takaran', 'satuan'];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_idproduk', 'idproduk');
    }
    public function bahan()
    {
        return $this->belongsTo(Bahan::class, 'bahan_idbahan', 'idbahan');
    }
}
