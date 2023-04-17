<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $casts = [
        'price' => 'double',
    ];

    // Fillable fields for mass assignment
    protected $fillable = [
        'name',
        'image',
        'price',
        'quantity',
        'user_id',
        'product_id',
        'total_price',
    ];

    // Relationship with User model
    public function user()
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    // Relationship with Product model
    public function product()
    {
        return $this->belongsTo(Product::class)->withDefault();
    }

    // Accessor for total price of cart item
    public function getTotalPriceAttribute()
    {
        return $this->price * $this->quantity;
    }

    // Accessor for total quantity of cart item
    public function getTotalQuantityAttribute()
    {
        return $this->quantity;
    }

    // Accessor for total price of cart item with currency
    public function getTotalPriceWithCurrencyAttribute()
    {
        return $this->getTotalPriceAttribute() . ' ' . config('app.currency');
    }

    protected static function booted()
    {
        static::updating(function ($cart) {
            $cart->total_price = $cart->price * $cart->quantity;
        });
    }
}
