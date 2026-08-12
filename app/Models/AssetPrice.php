<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetPrice extends Model
{
    protected $fillable = [
        'symbol',
        'current_price_inr',
        'change_24h',
        'last_updated_at',
    ];

    protected $casts = [
        'current_price_inr' => 'float',
        'change_24h' => 'float',
        'last_updated_at' => 'datetime',
    ];
}
