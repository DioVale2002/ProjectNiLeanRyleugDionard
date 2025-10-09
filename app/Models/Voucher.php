<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $primaryKey = 'voucher_id';
    
    protected $fillable = [
        'voucherName',
        'voucherType',
        'voucherAmount',
        'voucherUsed'
    ];

    protected $casts = [
        'voucherAmount' => 'integer',
        'voucherUsed' => 'integer',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class, 'voucher_id', 'voucher_id');
    }
}
