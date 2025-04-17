<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = 
    [
        'nama', 
        'no_telp', 
        'poin'
    ];

    public function penjualans()
    {
        return $this->hasMany(Penjualan::class);
    }
    

}

