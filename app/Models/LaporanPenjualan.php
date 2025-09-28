<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanPenjualan extends Model
{
    protected $table = 'view_laporan_penjualan';
    public $timestamps = false; // karena biasanya view gak punya created_at dan updated_at

    // public function scopeRangeTanggal($query, $value1, $value2)
    // {
    //     if ($value1 && $value2) {
    //         $query->whereBetween('tanggal', [$value1, $value2]);
    //     }
    // }
}
