<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'name',
        'location',
        'budget',
        'status',
        'start_date',
        'end_date',
        'description',
    ];

    public function flats()
    {
        return $this->hasMany(Flat::class);
    }

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function expenseSheets()
    {
        return $this->hasMany(ExpenseSheet::class);
    }

    public function expenseItems()
    {
        return $this->hasManyThrough(ExpenseItem::class, ExpenseSheet::class);
    }
}
