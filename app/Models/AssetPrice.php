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
}
