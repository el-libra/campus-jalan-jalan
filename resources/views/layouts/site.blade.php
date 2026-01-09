<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name'))</title>
        <link rel="icon" type="image/png" href="{{ asset('images/ush-logo.png') }}">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=dm-serif-display:400&family=sora:300,400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @stack('head')
    </head>
    <body class="text-slate-900">
        <div class="min-h-screen bg-[radial-gradient(circle_at_top,_var(--campus-sky)_0%,_transparent_60%)]">
            @yield('content')
        </div>

        @stack('scripts')
    </body>
</html>
