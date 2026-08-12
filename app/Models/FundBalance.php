<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FundBalance extends Model
{
    protected $fillable = [
        'available_cash',
        'notes',
    ];
}
