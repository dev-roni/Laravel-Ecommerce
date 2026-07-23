<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SanitizeInput
{
    // এই fields sanitize করব না
    private array $except = ['password', 'password_confirmation', 'body'];
    
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $input = $request->all();
        $this->sanitize($input);
        $request->merge($input);

        return $next($request);
    }

    private function sanitize(array &$data): void
    {
        foreach ($data as $key => &$value) {
            if (in_array($key, $this->except)) continue;

            if (is_array($value)) {
                $this->sanitize($value);
            } elseif (is_string($value)) {
                $value = strip_tags(trim($value));
            }
        }
    }
}
