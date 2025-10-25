<?php
namespace App\Models;
use App\Models\User;
use App\Models\Pembeliandtl;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pembelian extends Model
{
    use HasFactory;

    protected $table = 'pembelians';
    protected $primaryKey = 'idpembelian';
    protected $fillable = [
        'idpembelian',
        'tanggal',
        'supplier_idsupplier',
        'user_iduser',
        'created_at',
        'updated_at'
    ];

    //aktifkan jika id tidak increment
// public $keyType = 'string';
// public $incrementing = false;

    // public $timestamps = false;

    public function scopeSearch($query, $value)
    {
        $query->where('idpembelian', 'like', "%{$value}%")
            ->orWhere('tanggal', 'like', "%{$value}%")
            ->orWhere('supplier_idsupplier', 'like', "%{$value}%")
            ->orWhere('user_iduser', 'like', "%{$value}%")
            ->orWhere('created_at', 'like', "%{$value}%")
            ->orWhere('updated_at', 'like', "%{$value}%")
        ;
    }

    public function scopeRangeTanggal($query, $value1, $value2)
    {
        if ($value1 && $value2) {
            $query->whereBetween('tanggal', [$value1, $value2]);
        }
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_idsupplier', 'idsupplier');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_iduser', 'id');
    }

    public function pembeliandtls()
    {
        return $this->hasMany(Pembeliandtl::class, 'pembelian_idpembelian', 'idpembelian');
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
