<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $page['title'] }} | ImmoRadar</title>
        <meta name="description" content="{{ $page['description'] }}">
        <meta name="robots" content="index, follow">
        <link rel="canonical" href="{{ url('/guides/'.$slug) }}">
        <meta property="og:type" content="article">
        <meta property="og:locale" content="fr_FR">
        <meta property="og:title" content="{{ $page['title'] }}">
        <meta property="og:description" content="{{ $page['description'] }}">
        <meta property="og:url" content="{{ url('/guides/'.$slug) }}">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-slate-50 font-sans text-slate-900 antialiased">
        <header class="border-b border-slate-200 bg-white">
            <div class="mx-auto flex max-w-5xl items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
                <a href="{{ route('marketing.home') }}" class="inline-flex items-center gap-3 font-black">
                    <span class="grid h-10 w-10 place-items-center rounded-lg bg-teal-700 text-white">IR</span>
                    <span>ImmoRadar</span>
                </a>
                <div class="flex items-center gap-2">
                    <a href="{{ route('marketing.example') }}" class="ir-action-primary">Voir un exemple</a>
                    <a href="{{ route('marketing.trust') }}" class="ir-action-secondary hidden sm:inline-flex">Confidentialité</a>
                </div>
            </div>
        </header>

        <main>
            <article class="mx-auto max-w-5xl px-4 py-12 sm:px-6 lg:px-8">
                <a href="{{ route('marketing.home') }}" class="text-sm font-black text-teal-700 underline decoration-teal-200 underline-offset-4">Retour à ImmoRadar</a>
                <p class="mt-8 text-sm font-black uppercase text-teal-700">{{ $page['eyebrow'] }}</p>
                <h1 class="mt-3 max-w-4xl text-4xl font-black leading-tight text-slate-950 md:text-5xl">{{ $page['h1'] }}</h1>
                <p class="mt-6 max-w-3xl text-lg leading-8 text-slate-700">{{ $page['intro'] }}</p>

                <div class="mt-10 grid gap-4">
                    @foreach($page['sections'] as $section)
                        <section class="ir-panel bg-white p-6">
                            <h2 class="text-2xl font-black text-slate-950">{{ $section['title'] }}</h2>
                            <p class="mt-3 leading-7 text-slate-600">{{ $section['body'] }}</p>
                        </section>
                    @endforeach
                </div>

                <section class="mt-10 rounded-lg border border-amber-200 bg-amber-50 p-6">
                    <h2 class="text-2xl font-black text-amber-950">ImmoRadar sert à décider plus calmement.</h2>
                    <p class="mt-3 leading-7 text-amber-950">L'application ne remplace pas un professionnel. Elle aide à organiser les biens, préparer les visites, comparer les coûts et voir les alertes avant de trancher.</p>
                    <div class="mt-5 flex flex-wrap gap-3">
                        <a href="{{ route('marketing.example') }}" class="ir-action-primary">Voir un exemple complet</a>
                        @if(config('app.demo_login_enabled'))
                            <form method="POST" action="{{ route('login.demo') }}">
                                @csrf
                                <button type="submit" class="ir-action-secondary">Ouvrir la démo</button>
                            </form>
                        @endif
                        <a href="{{ route('marketing.trust') }}" class="ir-action-secondary">Confidentialité</a>
                    </div>
                </section>
            </article>
        </main>
    </body>
</html>
