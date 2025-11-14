<?php

namespace App\Models;

use App\Models\Hold;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Penjualan extends Model
{
    use HasFactory;

    protected $table = 'penjualans';
    protected $primaryKey = 'idpenjualan';
    public $timestamps = true;

    protected $fillable = [
        'kode_penjualan',
        'customer_name',
        'no_meja',
        'catatan',
        'tanggal',
        'total',
        'bayar',
        'kembalian',
        'user_iduser',
        'closed_by'
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    /**
     * Relasi many-to-one ke model User.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_iduser', 'id');
    }

    public function penjualanDtl()
    {
        return $this->hasMany(PenjualanDtl::class, 'penjualan_idpenjualan', 'idpenjualan')
            ->with('produk'); // optional, eager load produk langsung
    }
    public function detail()
    {
        return $this->hasMany(PenjualanDtl::class, 'penjualan_idpenjualan', 'idpenjualan');
    }

    // di App\Models\Penjualan.php
    public function hold()
    {
        return $this->hasMany(Hold::class, 'kode_transaksi', 'kode_penjualan');
    }


    public function scopeRangeTanggal($query, $value1, $value2)
    {
        if ($value1 && $value2) {
            $query->whereBetween('tanggal', [$value1, $value2]);
        }
    }
}
