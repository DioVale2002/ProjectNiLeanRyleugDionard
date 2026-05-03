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
        'voucherUsed',
        'valid_from',
        'valid_until',
        'minimum_order_amount',
        'max_uses',
        'per_customer_limit',
        'is_active',
    ];

    protected $casts = [
        'voucherAmount' => 'integer',
        'voucherUsed' => 'integer',
        'valid_from' => 'date',
        'valid_until' => 'date',
        'minimum_order_amount' => 'decimal:2',
        'max_uses' => 'integer',
        'per_customer_limit' => 'integer',
        'is_active' => 'boolean',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class, 'voucher_id', 'voucher_id');
    }

    public function scopeAvailableForCheckout($query)
    {
        return $query
            ->where('is_active', true)
            ->where(function ($subQuery) {
                $subQuery->whereNull('valid_from')
                    ->orWhereDate('valid_from', '<=', now()->toDateString());
            })
            ->where(function ($subQuery) {
                $subQuery->whereNull('valid_until')
                    ->orWhereDate('valid_until', '>=', now()->toDateString());
            });
    }
}
