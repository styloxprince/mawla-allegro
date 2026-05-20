<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpenseSheet extends Model
{
    protected $fillable = [
        'project_id',
        'expense_date',
        'total_amount',
        'note',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function items()
    {
        return $this->hasMany(ExpenseItem::class);
    }

    public function expenseItems()
    {
        return $this->hasMany(ExpenseItem::class);
    }
}
