<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    protected $fillable = [
        'project_id',
        'flat_id',
        'customer_id',
        'booking_date',

        'flat_size',
        'rate_per_sft',

        'total_price',
        'discount',
        'final_price',

        'status',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function flat(): BelongsTo
    {
        return $this->belongsTo(Flat::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(BookingPayment::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Calculated Attributes
    |--------------------------------------------------------------------------
    */

    public function getTotalPaidAttribute(): float
    {
        return (float) $this->payments()->sum('amount');
    }

    public function getDueAmountAttribute(): float
    {
        return (float) ($this->final_price - $this->total_paid);
    }

    public function getPaymentProgressAttribute(): float
    {
        if ($this->final_price <= 0) {
            return 0;
        }

        return round(($this->total_paid / $this->final_price) * 100, 2);
    }

    /*
    |--------------------------------------------------------------------------
    | Auto Update Payment Status
    |--------------------------------------------------------------------------
    */

    public function updatePaymentStatus(): void
    {
        if ($this->due_amount <= 0) {

            $this->status = 'completed';

        } elseif ($this->total_paid > 0) {

            $this->status = 'partial';

        } else {

            $this->status = 'booked';
        }

        $this->save();
    }
}