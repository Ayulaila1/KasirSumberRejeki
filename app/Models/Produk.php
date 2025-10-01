<?php
namespace App\Models;
use App\Models\Supplier;
use App\Models\ProdukRacikan;
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
        'kategori',
        'supplier_idsupplier',
        'harga_jual',
        'harga_beli',
        'tanggal_kedaluwarsa',
        'stok_minimum',
        'is_titipan',
        'created_at',
        'updated_at'
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
            ->orWhere('jenisproduk', 'like', "%{$value}%")
            ->orWhere('kategori', 'like', "%{$value}%")
            ->orWhere('supplier_idsupplier', 'like', "%{$value}%")
            ->orWhere('harga_jual', 'like', "%{$value}%")
            ->orWhere('harga_beli', 'like', "%{$value}%")
            ->orWhere('tanggal_kedaluwarsa', 'like', "%{$value}%")
            ->orWhere('stok_minimum', 'like', "%{$value}%")
            ->orWhere('is_titipan', 'like', "%{$value}%")
            ->orWhere('created_at', 'like', "%{$value}%")
            ->orWhere('updated_at', 'like', "%{$value}%")
        ;
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_idsupplier', 'idsupplier');
    }

    public function produkDetails()
    {
        return $this->hasMany(ProdukRacikan::class, 'produk_idproduk', 'idproduk');
    }

    public function getStokTersediaAttribute()
    {
        // Hitung stok dari bahan racikan
        $stokArray = $this->produkDetails->map(function ($racikan) {
            if ($racikan->bahan && $racikan->takaran > 0) {
                return floor($racikan->bahan->stok / $racikan->takaran);
            }
            return 0;
        });

        return $stokArray->min() ?? 0;
    }

    public function scopeFilterJenisProduk($query, $value)
    {
        if (!empty($value)) {
            $query->where('jenisproduk', $value);
        }
    }

    public function scopeFilterKategori($query, $value)
    {
        if (!empty($value)) {
            $query->where('kategori', $value);
        }
    }


    // public function kategoriproduk()
    // {
    //     return $this->belongsTo(KategoriProduk::class, 'kategoriproduk_idkategoriproduk', 'idkategoriproduk');
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
