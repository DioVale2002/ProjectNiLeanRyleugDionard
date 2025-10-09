<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $primaryKey = 'paymentMethod_id';
    
    protected $fillable = [
        'methodName'
    ];

    public function orders()
    {
        return $this->hasMany(Order::class, 'paymentMethod_id', 'paymentMethod_id');
    }
}

