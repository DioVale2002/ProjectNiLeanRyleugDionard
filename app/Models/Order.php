<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $primaryKey = 'order_id';
    
    protected $fillable = [
        'order_status',
        'order_date',
        'total_price',
        'voucher_id',
        'add_id',
        'paymentMethod_id',
        'payment_reference',
        'payment_proof_path',
        'payment_review_status',
        'cancellation_note',
        'is_first_party_delivery',
        'delivery_status',
        'resolved_at',
        'cus_id',
        'cart_id',
    ];

    protected $casts = [
        'order_date' => 'date',
        'total_price' => 'decimal:2',
        'is_first_party_delivery' => 'boolean',
        'resolved_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'cus_id', 'cus_id');
    }

    public function address()
    {
        return $this->belongsTo(Address::class, 'add_id', 'add_id');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'paymentMethod_id', 'paymentMethod_id');
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class, 'voucher_id', 'voucher_id');
    }

    public function cart()
    {
        return $this->belongsTo(Cart::class, 'cart_id', 'cart_id');
    }

    // Scopes for filtering orders
    public function scopeActive($query)
    {
        return $query->whereIn('order_status', ['Pending', 'Processing', 'Shipped', 'Delivered']);
    }

    public function scopeArchived($query)
    {
        return $query->whereIn('order_status', ['Completed', 'Cancelled', 'Failed']);
    }
}
