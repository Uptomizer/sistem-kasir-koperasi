<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $primaryKey = 'id_user';

    protected $fillable = [
        'nama_user',
        'username',
        'password',
        'role'
    ];

    protected $hidden = [
        'password'
    ];

    public function getAuthIdentifierName()
    {
        return 'id_user';
    }
    public function penjualan()
    {
    return $this->hasMany(Penjualan::class, 'id_user');
    }

}

