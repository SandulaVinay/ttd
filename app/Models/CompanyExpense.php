<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyExpense extends Model
{
    protected $fillable = [
        'title',
        'category',
        'amount',
        'expense_date',
        'paid_by',
        'receipt_url',
        'notes',
    ];
}
