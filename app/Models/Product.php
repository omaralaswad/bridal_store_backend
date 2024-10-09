<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'category_id',
        'image_url',
    ];

    // One product belongs to a category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // One product can have many order items
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // One product can have many reviews
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // One product can appear in many cart items
    public function cartItems()
    {
        return $this->hasMany(Cart::class);
    }

    // One product can appear in many wishlist items
    public function wishlistItems()
    {
        return $this->hasMany(Wishlist::class);
    }
}
