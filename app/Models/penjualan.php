<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    protected $fillable = [
        'nama_pelanggan',
        'member_id',
        'tanggal_penjualan',
        'status',
        'total_harga',
        'total_bayar',
        'kembalian',
        'poin_digunakan',
        'poin_didapat',
        'user_id',
    ];
    

    public function details()
{
    return $this->hasMany(DetailPenjualan::class);
}

public function member()
{
    return $this->belongsTo(Member::class);
}

public function user()
{
    return $this->belongsTo(User::class);
}

}
