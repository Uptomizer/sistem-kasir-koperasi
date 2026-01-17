<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    protected $table = 'penjualan';
    protected $primaryKey = 'id_penjualan';

    protected $fillable = [
        'tanggal',
        'id_user',
        'total',
    ];

    public $timestamps = true;
    public function detail()
{
    return $this->hasMany(DetailPenjualan::class, 'id_penjualan');
}

public function user()
{
    return $this->belongsTo(User::class, 'id_user');
}

}
