<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $primaryKey = 'cartitems_id';
    
    protected $fillable = [
        'quantity',
        'unitPrice',
        'subtotal',
        'cart_id',
        'product_ID'
    ];

    protected $casts = [
        'unitPrice' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class, 'cart_id', 'cart_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_ID', 'product_ID');
    }
}
