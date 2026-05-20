<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Flat extends Model
{
    protected $fillable = [
        'project_id',
        'flat_no',
        'unit',
        'floor',
        'size',
        'price',
        'status',
        'facing',
        'notes',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function customer()
{
    return $this->hasOne(Customer::class);
}

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
