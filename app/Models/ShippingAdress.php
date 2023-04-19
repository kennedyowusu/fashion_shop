<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingAdress extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address_line_1',
        'address_line_2',
        'phone',
        'city',
        'state',
        'zip',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    private function concatenateAddress()
    {
        return "{$this->address_line_1} {$this->address_line_2} {$this->city} {$this->state} {$this->zip}";
    }

    public function getFullAddressAttribute()
    {
        return $this->concatenateAddress();
    }

    public function getFullAddressWithPhoneAttribute()
    {
        return $this->concatenateAddress() . " {$this->phone}";
    }

    public function getFullAddressWithPhoneAndNameAttribute()
    {
        return "{$this->name} " . $this->concatenateAddress() . " {$this->phone}";
    }

    public function getFullAddressWithPhoneAndNameAndIdAttribute()
    {
        return "{$this->id} {$this->name} " . $this->concatenateAddress() . " {$this->phone}";
    }

    public function getShippingAddress(int $userId, array $data): bool
    {
        return self::where('user_id', $userId)
            ->where('name', $data['name'])
            ->where('address_line_1', $data['address_line_1'])
            ->where('phone', $data['phone'])
            ->where('city', $data['city'])
            ->where('state', $data['state'])
            ->where('zip', $data['zip'])
            ->exists();
    }

}
