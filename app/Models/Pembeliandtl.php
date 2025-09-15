<?php
namespace App\Models;
use App\Models\User;
use App\Models\Bahan;
use App\Models\Pembelian;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pembeliandtl extends Model
{
    use HasFactory;

    protected $table = 'pembeliandtls';
    protected $primaryKey = 'idpembeliandtl';
    protected $fillable = [
        'idpembeliandtl',
        'pembelian_idpembelian',
        'bahan_idbahan',
        'jumlah',
        'isi_per_satuan',
        'harga_beli',
        'subtotal',
        'created_at',
        'updated_at'
    ];

    //aktifkan jika id tidak increment
// public $keyType = 'string';
// public $incrementing = false;

    // public $timestamps = false;

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

    // public function kategoripembelian()
    // {
    //     return $this->belongsTo(Kategoripembelian::class, 'kategoripembelian_idkategoripembelian', 'idkategoripembelian');
    // }

    // public function scopeFilterpembelian($query, $value)
    // {
    //     if (!empty($value)) {
    //         $query->where('kategoripembelian_idkategoripembelian', $value);
    //     }
    // }

    // public function scopeFilterpembelian($query, $value)
    // {
    //     if (!empty($value)) {
    //         $query->where('pembelian_idpembelian', $value);
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
   public function status_table_detail()
   {
   return $this->hasMany(status_model_child::class, 'status_kolom_relasi_tabel_child', 'status_kolom_primary_key_table_parent');
   }
*/

    //contoh relasi untu mengambil data parent
/*
   public function status_table_parent()
   {
   return $this->belongsTo(status_model_parent::class, 'status_kolom_relasi_tabel_child', 'status_kolom_primary_key_table_parent');
   }
*/

}
