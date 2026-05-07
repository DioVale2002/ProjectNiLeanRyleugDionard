<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerLoginOtp extends Model
{
    protected $fillable = [
        'email',
        'code_hash',
        'expires_at',
        'consumed_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'consumed_at' => 'datetime',
    ];
}
