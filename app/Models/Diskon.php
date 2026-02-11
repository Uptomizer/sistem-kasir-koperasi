<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Diskon extends Model
{
    protected $table = 'discounts';
    protected $fillable = ['name', 'type', 'value', 'status', 'start_date', 'end_date'];
    
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];
}
