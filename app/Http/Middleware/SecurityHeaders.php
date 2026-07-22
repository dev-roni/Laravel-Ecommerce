<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set(
            'X-Content-Type-Options', 'nosniff'
        );
        $response->headers->set(
            'X-Frame-Options', 'SAMEORIGIN'
        );
        $response->headers->set(
            'X-XSS-Protection', '1; mode=block'
        );
        $response->headers->set(
            'Referrer-Policy', 'strict-origin-when-cross-origin'
        );
        $response->headers->set(
            'Permissions-Policy', 'camera=(), microphone=(), geolocation=()'
        );
        $response->headers->set(
            'Content-Security-Policy',
            "default-src 'self'; " .
            "script-src 'self' 'unsafe-inline' cdn.jsdelivr.net fonts.googleapis.com; " .
            "style-src 'self' 'unsafe-inline' cdn.jsdelivr.net fonts.googleapis.com; " .
            "font-src 'self' fonts.gstatic.com cdn.jsdelivr.net; " .
            "img-src 'self' data: lh3.googleusercontent.com; " .
            "connect-src 'self';"
        );

        return $response;
    }
}
