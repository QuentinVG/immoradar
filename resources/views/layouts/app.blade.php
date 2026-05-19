<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="robots" content="noindex, nofollow">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-slate-900">
        <div class="min-h-screen">
            @include('layouts.navigation')
            @auth
                @if(Auth::user()->isDemoAccount())
                    <div class="border-b border-amber-200 bg-amber-50 px-4 py-2 text-center text-sm font-semibold text-amber-950">
                        Compte démo en lecture seule. Crée ton propre compte pour modifier les données.
                    </div>
                @endif
            @endauth

            @isset($header)
                <header class="border-b border-white/70 bg-white/60 shadow-sm shadow-slate-200/60 backdrop-blur">
                    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
