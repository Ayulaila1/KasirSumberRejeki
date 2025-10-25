<?php
namespace App\Models;
use App\Models\Pengeluaran;
use App\Models\Pembeliandtl;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bahan extends Model
{
    use HasFactory;

    protected $table = 'bahans';
    protected $primaryKey = 'idbahan';
    protected $fillable = [
        'idbahan',
        'nama',
        'stok',
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
        $query->where('idbahan', 'like', "%{$value}%")
            ->orWhere('nama', 'like', "%{$value}%")
            ->orWhere('stok', 'like', "%{$value}%")
            ->orWhere('satuan', 'like', "%{$value}%")
            ->orWhere('created_at', 'like', "%{$value}%")
            ->orWhere('updated_at', 'like', "%{$value}%")
        ;
    }

    // Relasi ke detail pembelian
    public function pembeliandtls()
    {
        return $this->hasMany(Pembeliandtl::class, 'bahan_idbahan', 'idbahan');
    }

    // Relasi ke pengeluaran
    public function pengeluarans()
    {
        return $this->hasMany(Pengeluaran::class, 'bahan_idbahan', 'idbahan');
    }

    // public function kategoribahan()
    // {
    //     return $this->belongsTo(Kategoribahan::class, 'kategoribahan_idkategoribahan', 'idkategoribahan');
    // }

    // public function bahan()
    // {
    //     return $this->belongsTo(Bahan::class, 'bahan_idbahan', 'idbahan');
    // }

    // public function scopeFilterbahan($query, $value)
    // {
    //     if (!empty($value)) {
    //         $query->where('kategoribahan_idkategoribahan', $value);
    //     }
    // }

    // public function scopeFilterBahan($query, $value)
    // {
    //     if (!empty($value)) {
    //         $query->where('bahan_idbahan', $value);
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
