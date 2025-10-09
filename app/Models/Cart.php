<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $primaryKey = 'cart_id';
    
    protected $fillable = [
        'createdDate',
        'status',
        'cus_id'
    ];

    protected $casts = [
        'createdDate' => 'date',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'cus_id', 'cus_id');
    }

    public function items()
    {
        return $this->hasMany(CartItem::class, 'cart_id', 'cart_id');
    }

    public function order()
    {
        return $this->hasOne(Order::class, 'cart_id', 'cart_id');
    }
}
