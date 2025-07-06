<?php
namespace App\Models;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LaporanPendapatan extends Model
{
    use HasFactory;

    protected $table = 'view_laporan_pendapatan_bulanan';
    public $timestamps = false; // karena biasanya view gak punya created_at dan updated_at


}
