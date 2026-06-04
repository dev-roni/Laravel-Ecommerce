<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <style>
            body {
                background: #F8FAFC;
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .auth-card {
                background: #fff;
                border: 0.5px solid rgba(0,0,0,0.1);
                border-radius: 16px;
                padding: 2rem;
                width: 100%;
                max-width: 400px;
            }
            .brand-icon {
                width: 44px;
                height: 44px;
                border-radius: 10px;
                background: #0A2540;
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }
            .btn-purple {
                background: #1DA1A8;
                border: none;
                border-radius: 10px;
                color: #fff;
                font-size: 14px;
                font-weight: 500;
                height: 42px;
            }
            .btn-purple:hover { background: #1F2933; color: #fff; }
            .btn-social {
                border: 0.5px solid rgba(0,0,0,0.15);
                border-radius: 10px;
                background: #fff;
                color: #333;
                font-size: 13px;
                font-weight: 500;
                height: 42px;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
                text-decoration: none;
                width: 100%;
            }
            .btn-social:hover { background: #f5f5f0; color: #333; }
            .form-control {
                border: 0.5px solid rgba(0,0,0,0.2);
                border-radius: 10px;
                height: 42px;
                font-size: 14px;
            }
            .form-control:focus {
                border-color: #E5E7EB;
                box-shadow: 0 0 0 3px #EEEDFE;
            }
            .divider {
                display: flex;
                align-items: center;
                gap: 10px;
                color: #E5E7EB;
                font-size: 12px;
            }
            .divider::before, .divider::after {
                content: '';
                flex: 1;
                height: 0.5px;
                background: rgba(0,0,0,0.1);
            }
            </style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans  ">
        <div class="auth-card shadow-sm">
            <div>
                <a href="/">
                    <x-application-logo class="w-20 h-20 text-secondary" />
                </a>
            </div>

            <div class="w-100 mt-3 px-4 py-3 bg-white shadow overflow-hidden rounded-sm-2" style="max-width: 448px;">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
