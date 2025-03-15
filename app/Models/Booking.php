<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id', 'service_id', 'date', 'status',
    ];

    /**
     * A booking belongs to a user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * A booking belongs to a service.
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * A booking has one payment.
     */
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
