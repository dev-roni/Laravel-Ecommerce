<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Facades\Cache;
use App\Models\AllowedIp;

class AdminIpWhiteList
{

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
         // শুধু ডাটাবেজে থাকা IP থেকে admin panel access হবে
        $allowedIps = Cache::rememberForever('allowed_ips', function() {
            return AllowedIp::where('is_active',true)
            ->pluck('ip')
            ->toArray();
        });

        if (!in_array($request->ip(), $allowedIps)) {
            abort(403, 'Access Denied');
        }

        return $next($request);
    }
}
