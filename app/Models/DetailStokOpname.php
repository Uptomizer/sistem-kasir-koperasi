<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\StokOpname;
use App\Models\Barang;

class DetailStokOpname extends Model
{
    protected $table = 'detail_stok_opname';
    protected $primaryKey = 'id_detail_stok_opname';

    protected $fillable = [
        'id_stok_opname',
        'id_barang',
        'stok_sistem',
        'stok_fisik',
        'selisih',
        'keterangan'
    ];
    
    public function stok_opname()
    {
        return $this->belongsTo(StokOpname::class, 'id_stok_opname', 'id_stok_opname');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
    }
}
