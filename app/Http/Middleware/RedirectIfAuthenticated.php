<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            if(auth()->user()->isAdmin()){
                return redirect('/admin/dashboard');
            }
            if(!auth()->user()->isAdmin()){
                return redirect('/');
            }
        }

        return $next($request);
    }
}