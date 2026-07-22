<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'পবনবাহিকা')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/admin.css','resources/js/admin.js'])
    
</head>
<body>