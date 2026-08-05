<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminIpWhiteList
{
    // শুধু এই IP থেকে admin panel access হবে
    private array $allowedIps;

    public function __construct()
    {
        $this->allowedIps = config('security.allowed_ips');
    }
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!in_array($request->ip(), $this->allowedIps)) {
            abort(403, 'Access Denied');
        }

        return $next($request);
    }
}
