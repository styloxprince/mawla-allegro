<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingPayment extends Model
{
    protected $fillable = [
        'booking_id',
        'payment_date',
        'amount',
        'payment_method',
        'bank_id',
        'transaction_id',
        'note',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Auto Update Booking Status
    |--------------------------------------------------------------------------
    */

    protected static function booted(): void
    {
        static::created(function ($payment) {
            $payment->booking?->updatePaymentStatus();
        });

        static::updated(function ($payment) {
            $payment->booking?->updatePaymentStatus();
        });

        static::deleted(function ($payment) {
            $payment->booking?->updatePaymentStatus();
        });
    }
}