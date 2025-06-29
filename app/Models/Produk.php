<?php
namespace App\Models;
use App\Models\Supplier;
use App\Models\Pembeliandtl;
use App\Models\Penjualandtl;
use App\Models\KategoriProduk;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produks';
    protected $primaryKey = 'idproduk';
    protected $fillable = [
        'idproduk',
        'nama',
        'image',
        'jenisproduk',
        'satuan',
        'supplier_idsupplier',
        'harga_jual',
        'harga_beli',
        'tanggal_kedaluwarsa',
        'stok_minimum',
    ];
    //aktifkan jika id tidak increment
// public $keyType = 'string';
// public $incrementing = false;

    // public $timestamps = false;

    public function scopeSearch($query, $value)
    {
        $query->where('idproduk', 'like', "%{$value}%")
            ->orWhere('nama', 'like', "%{$value}%")
            ->orWhere('image', 'like', "%{$value}%")
            ->orWhere('kategoriproduk_idkategoriproduk', 'like', "%{$value}%")
            ->orWhere('model', 'like', "%{$value}%")
            ->orWhere('supplier_idsupplier', 'like', "%{$value}%")
            ->orWhere('satuan', 'like', "%{$value}%")
            ->orWhere('harga_jual', 'like', "%{$value}%")
            ->orWhere('harga_beli', 'like', "%{$value}%")
            ->orWhere('tanggal_kedaluwarsa', 'like', "%{$value}%")
            ->orWhere('stok_minimum', 'like', "%{$value}%")
        ;
    }

    // public function kategoriproduk()
    // {
    //     return $this->belongsTo(KategoriProduk::class, 'kategoriproduk_idkategoriproduk', 'idkategoriproduk');
    // }

    // public function supplier()
    // {
    //     return $this->belongsTo(Supplier::class, 'supplier_idsupplier', 'idsupplier');
    // }

    // public function scopeFilterProduk($query, $value)
    // {
    //     if (!empty($value)) {
    //         $query->where('kategoriproduk_idkategoriproduk', $value);
    //     }
    // }

    // public function scopeFilterSupplier($query, $value)
    // {
    //     if (!empty($value)) {
    //         $query->where('supplier_idsupplier', $value);
    //     }
    // }
    /*
    public function scopeRangeTanggal($query, $value1, $value2)
    {
       if ($value1 && $value2) {
           $query->whereBetween('tanggal', [$value1, $value2]);
       }
    }
    */


    //contoh relasi untu menampilkan tabel child atau detail 
/*
   public function nama_table_detail()
   {
   return $this->hasMany(nama_model_child::class, 'nama_kolom_relasi_tabel_child', 'nama_kolom_primary_key_table_parent'); 
   }
*/

    //contoh relasi untu mengambil data parent
/*
   public function nama_table_parent()
   {
   return $this->belongsTo(nama_model_parent::class, 'nama_kolom_relasi_tabel_child', 'nama_kolom_primary_key_table_parent'); 
   }
*/

}
