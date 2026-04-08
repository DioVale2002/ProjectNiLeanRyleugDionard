<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockOut extends Model
{
    protected $table = 'stock_out';
    protected $primaryKey = 'stockOut_id';

    protected $fillable = [
        'stockOut_date',
        'productOut',
    ];

    protected $casts = [
        'stockOut_date' => 'date',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'productOut', 'product_ID');
    }
}