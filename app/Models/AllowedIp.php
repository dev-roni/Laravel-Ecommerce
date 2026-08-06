<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class AllowedIp extends Model
{
    protected $fillable = [
        'user_id',
        'ip',
        'description',
        'is_active',
        
    ];

    protected static function booted(): void
    {
        foreach(['created','updated','delated'] as $event){
            static::$event(
                function(){
                    Cache::forgot('allowed_ips');
                }
            );

        }
    }
}
