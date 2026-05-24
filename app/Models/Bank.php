<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bank extends Model
{
    protected $fillable = [
        'bank_name',
        'account_name',
        'account_number',
        'branch',
    ];

    public function bookingPayments(): HasMany
    {
        return $this->hasMany(BookingPayment::class);
    }
}
