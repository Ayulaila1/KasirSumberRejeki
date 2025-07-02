<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    use HasFactory;
    protected $table = 'pembelians';
    protected $primaryKey = 'idpembelian';
    public $timestamps = true;
    protected $fillable = ['tanggal', 'supplier_idsupplier', 'user_iduser', 'total item', 'total_hargabeli'];
    protected $casts = ['tanggal' => 'date'];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_idsupplier', 'idsupplier');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_iduser', 'id');
    }
    public function details()
    {
        return $this->hasMany(PembelianDtl::class, 'pembelian_idpembelian', 'idpembelian');
    }
}
