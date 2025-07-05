<?php
namespace App\Models;

use App\Models\Bahan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PembelianDtl extends Model
{
    use HasFactory;
    protected $table = 'pembeliandtls';
    protected $primaryKey = 'idpembeliandtl';
    public $timestamps = true;
    protected $fillable = ['idpembeliandtl', 'pembelian_idpembelian', 'bahan_idbahan', 'jumlah', 'isi_per_satuan', 'harga_beli', 'subtotal'];

    public function scopeSearch($query, $value)
    {
        $query->where('idpembeliandtl', 'like', "%{$value}%")
            ->orWhere('pembelian_idpembelian', 'like', "%{$value}%")
            ->orWhere('bahan_idbahan', 'like', "%{$value}%")
            ->orWhere('jumlah', 'like', "%{$value}%")
            ->orWhere('isi_per_satuan', 'like', "%{$value}%")
            ->orWhere('harga_beli', 'like', "%{$value}%")
            ->orWhere('subtotal', 'like', "%{$value}%")
            ->orWhere('created_at', 'like', "%{$value}%")
            ->orWhere('updated_at', 'like', "%{$value}%")
        ;
    }

    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class, 'pembelian_idpembelian', 'idpembelian');
    }
    public function bahan()
    {
        return $this->belongsTo(Bahan::class, 'bahan_idbahan', 'idbahan');
    }
}
