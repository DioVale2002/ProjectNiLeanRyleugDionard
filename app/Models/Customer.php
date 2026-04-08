<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Customer extends Authenticatable
{
    use Notifiable;

    protected $primaryKey = 'cus_id';
    
    protected $fillable = [
        'first_name',
        'last_name',
        'contact_num',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    public function address()
    {
        return $this->hasOne(Address::class, 'cus_id', 'cus_id');
    }

    public function orders()
    {
    return $this->hasMany(Order::class, 'cus_id', 'cus_id');
    }

public function carts()
    {
    return $this->hasMany(Cart::class, 'cus_id', 'cus_id');
    }
}