<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerActionOtp extends Model
{
    protected $fillable = [
        'email',
        'action',
        'payload',
        'code_hash',
        'expires_at',
        'consumed_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'expires_at' => 'datetime',
        'consumed_at' => 'datetime',
    ];
}
