<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PortfolioAsset extends Model
{
    protected $fillable = [
        'symbol',
        'name',
        'asset_type',
        'quantity',
        'buy_price',
        'sell_price',
        'buy_sell_charges',
        'investment_amount',
        'api_identifier',
        'notes',
    ];

    public function price()
    {
        return $this->hasOne(AssetPrice::class, 'symbol', 'symbol');
    }
}
