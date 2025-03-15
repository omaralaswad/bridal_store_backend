<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['user_id', 'booking_id', 'amount', 'status', 'payment_date'];

    /**
     * A payment belongs to a user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * A payment belongs to a booking.
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
