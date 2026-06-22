<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckBanned
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->is_banned) {
            auth()->logout();
            return redirect()->route('login')
                ->with('error', 'আপনার account সাময়িকভাবে নিষ্ক্রিয় করা হয়েছে। সাহায্যের জন্য যোগাযোগ করুন।');
        }

        return $next($request);
    }
}
