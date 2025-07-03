<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReturTitipan extends Model
{
    use HasFactory;

    protected $table = 'retur_titipan';
    protected $primaryKey = 'idretur_titipan';
    protected $fillable = [
        'idretur_titipan',
        'tanggal',
        'supplier_idsupplier',
        'produk_idproduk',
        'qty',
        'keterangan',
        'created_at',
        'updated_at'
    ];
    //aktifkan jika id tidak increment
// public $keyType = 'string';
// public $incrementing = false;

    // public $timestamps = false;

    public function scopeSearch($query, $value)
    {
        $query->where('idretur_titipan', 'like', "%{$value}%")
            ->orWhere('tanggal', 'like', "%{$value}%")
            ->orWhere('supplier_idsupplier', 'like', "%{$value}%")
            ->orWhere('produk_idproduk', 'like', "%{$value}%")
            ->orWhere('qty', 'like', "%{$value}%")
            ->orWhere('keterangan', 'like', "%{$value}%")
        ;
    }

    // public function kategorisupplier()
    // {
    //     return $this->belongsTo(Kategorisupplier::class, 'kategorisupplier_idkategorisupplier', 'idkategorisupplier');
    // }

    // public function supplier()
    // {
    //     return $this->belongsTo(ProdukRacikan::class, 'supplier_idretur_titipan', 'idretur_titipan');
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
    //         $query->where('supplier_idretur_titipan', $value);
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
