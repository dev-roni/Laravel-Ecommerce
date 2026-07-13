<!DOCTYPE html>
<html lang="bn">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'পবনবাহিকা') — Modern Living</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<script>
  
  window.App = {
    csrfToken: @json(csrf_token()),
    cartAddUrl: @json(route('cart.add')),
    wishlistToggle: @json(route('wishlist.toggle')),
    loginUrl: @json(route('login')),
    recentViewClear: @json(route('recently-viewed.clear')),
  };
</script>
@vite(['resources/css/app.css','resources/js/app.js'])
@stack('styles')
</head>