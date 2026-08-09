<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IdempotencyKey extends Model
{
    protected $fillable = [
        'key',
        'user_id', 
        'endpoint',
        'response_status', 
        'response_body', 
        'expires_at',
    ];

    protected $casts = [
        'response_body' => 'array',
        'expires_at'    => 'datetime',
    ];

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }
}
