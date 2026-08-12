<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Investor extends Model
{
    protected $fillable = [
        'name',
        'type',
        'total_contributed',
        'notes',
    ];

    public function contributions(): HasMany
    {
        return $this->hasMany(InvestorContribution::class);
    }
}
