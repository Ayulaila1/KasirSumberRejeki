<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanPenjualan extends Model
{
    protected $table = 'view_laporan_penjualan';
    public $timestamps = false; // karena biasanya view gak punya created_at dan updated_at
}