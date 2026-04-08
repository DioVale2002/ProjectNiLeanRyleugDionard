<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockIn extends Model
{
    protected $table = 'stock_in';
    protected $primaryKey = 'stockIn_id';

    protected $fillable = [
        'stockIn_date',
        'productIn',
    ];

    protected $casts = [
        'stockIn_date' => 'date',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'productIn', 'product_ID');
    }
}