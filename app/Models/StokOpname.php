<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\DetailStokOpname;
use App\Models\User;

class StokOpname extends Model
{
    protected $table = 'stok_opname';
    protected $primaryKey = 'id_stok_opname';
    
    protected $fillable = [
        'kode_opname',
        'tanggal',
        'id_user',
        'status',
        'catatan'
    ];

    public function detail()
    {
        return $this->hasMany(DetailStokOpname::class, 'id_stok_opname', 'id_stok_opname');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
