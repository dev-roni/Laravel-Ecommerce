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
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if (!$user->isAdmin()) {
            abort(403);
        }

        if (!$user->hasTwoFactorEnabled()) {
            auth()->logout();

            session([
                'auth_user_id'  => $user->id,
                'auth_remember' => false,
                'auth_method'   => '2fa_setup',
            ]);

            return redirect()->route('2fa.setup')
                ->with('warning', 'Admin panel ব্যবহার করতে Authenticator setup করুন।');
        }

        return $next($request);
    }
}
