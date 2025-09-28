<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hold extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_transaksi',
        'customer',
        'table_number',
        'items',
        'total',
        'user_id',
    ];

    protected $casts = [
        'items' => 'array',
    ];

    // relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
