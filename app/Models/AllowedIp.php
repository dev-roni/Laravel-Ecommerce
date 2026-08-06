<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AllowedIp extends Model
{
    protected $fillable = [
        'user_id',
        'ip',
        'description',
        'is_active',
        
    ];
}
