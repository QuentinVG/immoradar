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
    <body class="font-sans text-slate-900 antialiased">
        <div class="grid min-h-screen lg:grid-cols-[0.95fr_1.05fr]">
            <section class="flex min-h-72 flex-col justify-between bg-slate-950 px-6 py-8 text-white sm:px-10 lg:px-14">
                <a href="/" class="inline-flex items-center gap-3 font-black">
                    <span class="grid h-10 w-10 place-items-center rounded-lg bg-teal-400 text-slate-950">IR</span>
                    <span>ImmoRadar</span>
                </a>
                <div class="max-w-xl py-10 lg:py-0">
                    <p class="text-sm font-black uppercase text-amber-300">Anti-coup de cœur</p>
                    <h1 class="mt-4 text-4xl font-black leading-tight sm:text-5xl">Décide moins vite. Décide mieux.</h1>
                    <p class="mt-5 text-base leading-7 text-slate-300">Projet, biens, visite, coût réel, alertes et verdict. Tout est pensé pour comprendre en quelques secondes si un bien mérite d’être creusé.</p>
                </div>
                <div class="grid gap-3 text-sm text-slate-300 sm:grid-cols-3">
                    <span class="rounded-lg border border-white/10 bg-white/5 p-3">Coût réel</span>
                    <span class="rounded-lg border border-white/10 bg-white/5 p-3">Checklist visite</span>
                    <span class="rounded-lg border border-white/10 bg-white/5 p-3">Verdict clair</span>
                </div>
            </section>

            <div class="flex items-center justify-center px-4 py-8 sm:px-6 lg:px-10">
                <div class="w-full max-w-md rounded-lg border border-white/80 bg-white/90 p-6 shadow-xl shadow-slate-300/30 backdrop-blur sm:p-8">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
