<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPenjualan extends Model
{
    protected $table = 'detail_penjualan';
    protected $primaryKey = 'id_detail';

    protected $fillable = [
        'id_penjualan',
        'id_barang',
        'jumlah',
        'harga',
        'subtotal',
    ];

    public $timestamps = false;

    // Relasi
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }
}
