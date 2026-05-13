<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!auth()->check()){
            return redirect()->route('login');
        }
        if(!auth()->user()->isAdmin()){
            abort(403,'এই পেজে প্রবেশের অনুমতি নেই');
        }
        return $next($request);
    }
}
