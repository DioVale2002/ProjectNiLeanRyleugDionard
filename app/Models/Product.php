<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $primaryKey = 'product_ID';
    
    protected $fillable = [
        'Title',
        'Author',
        'Rating',
        'Review',
        'Price',
        'Stock',
        'ISBN',
        'Publisher',
        'Genre',
        'Age_Group',
        'Length',
        'Width'
    ];

    protected $casts = [
        'Rating' => 'decimal:2',
        'Price' => 'decimal:2',
    ];

    public function cartItems()
    {
        return $this->hasMany(CartItem::class, 'product_ID', 'product_ID');
    }
}
