<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Archive extends Model
{
    protected $table = 'archive';
    protected $primaryKey = 'archived_id';

    protected $fillable = [
        'archived_date',
        'archivedProduct',
    ];

    protected $casts = [
        'archived_date' => 'date',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'archivedProduct', 'product_ID');
    }
}