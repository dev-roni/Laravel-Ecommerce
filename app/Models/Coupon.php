<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code', 
        'type', 
        'value', 
        'min_order_amount', 
        'max_discount',
        'usage_limit', 
        'used_count', 
        'per_user_limit',
        'starts_at', 
        'expires_at', 
        'is_active',
    ];

    protected $casts = [
        'starts_at'  => 'date',
        'expires_at' => 'date',
        'is_active'  => 'boolean',
        'value'      => 'decimal:2',
    ];
}
