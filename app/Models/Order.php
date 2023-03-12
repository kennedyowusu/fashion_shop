<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'total_price',
        'shipping_address',
        'billing_address',
        'user_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('latest', function (Builder $builder) {
            $builder->latest();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shipping()
    {
        return $this->hasOne(Shipping::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // Filter orders by status
    public function scopePending(Builder $query)
    {
        return $query->where('status', 'pending');
    }

    // Filter orders by status
    public function scopeProcessed(Builder $query)
    {
        return $query->whereIn('status', ['processing', 'shipped', 'delivered']);
    }
}

