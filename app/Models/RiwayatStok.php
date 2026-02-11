<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Barang;
use App\Models\User;

class RiwayatStok extends Model
{
    protected $table = 'riwayat_stok';
    protected $primaryKey = 'id_riwayat';
    
    protected $fillable = [
        'id_barang',
        'id_user',
        'jenis',
        'jumlah',
        'stok_awal',
        'stok_akhir',
        'referensi',
        'keterangan'
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
