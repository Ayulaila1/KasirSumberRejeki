<?php
namespace App\Models;
use App\Models\Bahan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProdukRacikan extends Model
{
    use HasFactory;

    protected $table = 'produk_racikans';
    protected $primaryKey = 'idproduk_racikan';
    protected $fillable = [
        'idproduk_racikan',
        'produk_idproduk',
        'bahan_idbahan',
        'takaran',
        'satuan',
        'created_at',
        'updated_at'
    ];
    //aktifkan jika id tidak increment
// public $keyType = 'string';
// public $incrementing = false;

    // public $timestamps = false;

    public function scopeSearch($query, $value)
    {
        $query->where('idproduk_racikan', 'like', "%{$value}%")
            ->orWhere('produk_idproduk', 'like', "%{$value}%")
            ->orWhere('bahan_idbahan', 'like', "%{$value}%")
            ->orWhere('takaran', 'like', "%{$value}%")
            ->orWhere('satuan', 'like', "%{$value}%")
        ;
    }

    public function bahan()
    {
        return $this->belongsTo(Bahan::class, 'bahan_idbahan', 'idbahan');
    }

    // public function supplier()
    // {
    //     return $this->belongsTo(ProdukRacikan::class, 'supplier_idproduk_racikan', 'idproduk_racikan');
    // }

    // public function scopeFiltersupplier($query, $value)
    // {
    //     if (!empty($value)) {
    //         $query->where('kategorisupplier_idkategorisupplier', $value);
    //     }
    // }

    // public function scopeFilterProdukRacikan($query, $value)
    // {
    //     if (!empty($value)) {
    //         $query->where('supplier_idproduk_racikan', $value);
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
