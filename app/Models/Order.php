<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'total_amount',
        'payment_id',
    ];

    // One order belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // One order has many order items
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // One order has one payment
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}

