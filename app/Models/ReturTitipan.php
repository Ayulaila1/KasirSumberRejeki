<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturTitipan extends Model
{
    use HasFactory;
    protected $table = 'retur_titipan';
    protected $primaryKey = 'idretur_titipan';
    public $timestamps = true;
    protected $fillable = ['tanggal', 'supplier_idsupplier', 'produk_idproduk', 'qty', 'keterangan'];
    protected $casts = ['tanggal' => 'date'];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_idsupplier', 'idsupplier');
    }
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_idproduk', 'idproduk');
    }
}
