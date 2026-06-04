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
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="auth-page">
        <div class="auth-card mb-4">
            <div class="text-center mb-4">
                <x-application-logo  />
            </div>
            <x-auth-session-status class="mb-4" :status="session('status')" />

            
                {{ $slot }}

        </div>
        <script>
        function togglePassword() {
            const field = document.getElementById('password');

            field.type =
                field.type === 'password'
                    ? 'text'
                    : 'password';
        }
        </script>
    </body>
</html>
