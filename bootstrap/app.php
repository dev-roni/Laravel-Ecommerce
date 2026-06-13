<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/frontend.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
        Route::middleware(['web'])
            ->prefix('admin')
            ->name('admin.')
            ->group(base_path('routes/backend.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {

        // ── CSRF except ──
        $middleware->validateCsrfTokens(except: [
            'payment/success',
            'payment/fail',
            'payment/cancel',
            'payment/ipn',
        ]);

        //middleware regi
        $middleware->alias([
            'admin'=>App\Http\Middleware\AdminMiddleware::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create(); 
