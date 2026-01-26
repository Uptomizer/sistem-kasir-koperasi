<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';
    protected $primaryKey = 'id_barang';

    protected $fillable = [
        'nama_barang',
        'kode_barang',
        'id_kategori',
        'harga_beli',
        'harga_jual',
        'stok',
    ];

    public $timestamps = true;

    // Relasi
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }
}
