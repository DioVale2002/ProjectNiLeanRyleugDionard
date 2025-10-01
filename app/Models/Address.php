<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $primaryKey = 'add_id';
    
    protected $fillable = [
        'country',
        'province',
        'city',
        'barangay',
        'zip_postal_code',
        'cus_id',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'cus_id', 'cus_id');
    }
}