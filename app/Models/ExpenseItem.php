<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpenseItem extends Model
{
    protected $fillable = [
        'expense_sheet_id',
        'category',
        'description',
        'amount',
    ];

    public function sheet()
    {
        return $this->belongsTo(ExpenseSheet::class);
    }

    public function expenseSheet()
    {
        return $this->belongsTo(ExpenseSheet::class);
    }
}
