<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    public const CATEGORIES = [
        'Food', 'Transport', 'Bills', 'Groceries', 'Shopping',
        'Health', 'Entertainment', 'Farm Input', 'Other',
    ];

    protected $fillable = ['amount', 'category', 'date', 'note'];

    protected function casts(): array
    {
        return [
            'amount' => 'float',
            'date' => 'date:Y-m-d',
        ];
    }
}
