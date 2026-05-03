<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $primaryKey = 'product_ID';

    protected $fillable = [
        'Title', 'Author', 'Rating', 'Review',
        'Description',
        'Price', 'Stock', 'ISBN', 'Publisher',
        'Genre', 'Format', 'Language', 'Publication_Date', 'Subject', 'Branch',
        'Age_Group', 'Length', 'Width',
    ];

    protected $casts = [
        'Rating' => 'decimal:2',
        'Price'  => 'decimal:2',
        'Publication_Date' => 'date',
    ];

    public function cartItems()
    {
        return $this->hasMany(CartItem::class, 'product_ID', 'product_ID');
    }

    public function stockIns()
    {
        return $this->hasMany(StockIn::class, 'productIn', 'product_ID');
    }

    public function stockOuts()
    {
        return $this->hasMany(StockOut::class, 'productOut', 'product_ID');
    }

    public function archives()
    {
        return $this->hasMany(Archive::class, 'archivedProduct', 'product_ID');
    }

    public function sales()
    {
        return $this->hasMany(Sale::class, 'product_id', 'product_ID');
    }
}