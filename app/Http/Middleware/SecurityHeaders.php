<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set(
            'X-Content-Type-Options', 'nosniff'
        );
        $response->headers->set(
            'X-Frame-Options', 'SAMEORIGIN'
        );

        // X-XSS-Protection আধুনিক ব্রাউজারে Deprecated
        $response->headers->set(
            'X-XSS-Protection', '1; mode=block'
        );
        $response->headers->set(
            'Referrer-Policy', 'strict-origin-when-cross-origin'
        );
        $response->headers->set(
            'Permissions-Policy', 'camera=(), microphone=(), geolocation=()'
        );


        if (app()->isLocal()) {

            // Development (Vite)
            $csp = "
                default-src 'self';

                script-src 'self' 'unsafe-inline' 'unsafe-eval'
                    http://127.0.0.1:5173
                    https://cdn.jsdelivr.net;

                style-src 'self' 'unsafe-inline'
                    http://127.0.0.1:5173
                    https://fonts.googleapis.com;

                font-src 'self'
                    data:
                    http://127.0.0.1:5173
                    https://fonts.gstatic.com;
                img-src 'self'
                    data:
                    blob:
                    http://localhost:8000
                    http://127.0.0.1:8000
                    https://lh3.googleusercontent.com
                    https://fastly.picsum.photos/seed/admin/40/40
                    https://picsum.photos/seed/admin/40/40;
                connect-src 'self'
                    http://localhost:5173
                    http://127.0.0.1:5173
                    http://[::1]:5173
                    ws://localhost:5173
                    ws://127.0.0.1:5173
                    ws://[::1]:5173;
            ";

        } else {

            // Production
            $csp = "
                default-src 'self'; 
                script-src 'self' 'unsafe-inline' cdn.jsdelivr.net fonts.googleapis.com; 
                style-src 'self' 'unsafe-inline' cdn.jsdelivr.net fonts.googleapis.com; 
                font-src 'self' fonts.gstatic.com cdn.jsdelivr.net;
                img-src 'self' data: lh3.googleusercontent.com;
                connect-src 'self';
            ";
        }

        $response->headers->set(
            'Content-Security-Policy',
            preg_replace('/\s+/', ' ', trim($csp))
        );

        return $response;
    }
}