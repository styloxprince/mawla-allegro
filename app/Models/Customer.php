<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'project_id',
        'flat_id',
        'name',
        'phone',
        'email',
        'nid',
        'address',
        'nominee_name',
        'nominee_phone',
        'booking_date',
        'total_amount',
        'notes',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function flat()
    {
        return $this->belongsTo(Flat::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function bookingPayments()
    {
        return $this->hasManyThrough(BookingPayment::class, Booking::class);
    }
}
