<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Exemple complet Maison Montoison | ImmoRadar</title>
        <meta name="description" content="Exemple complet d'un rapport ImmoRadar avant offre : coût réel, alertes, documents à demander et décision à sécuriser.">
        <meta name="robots" content="index, follow">
        <link rel="canonical" href="{{ route('marketing.example') }}">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-slate-50 font-sans text-slate-900 antialiased">
        <header class="border-b border-slate-200 bg-white">
            <div class="mx-auto flex max-w-6xl items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
                <a href="{{ route('marketing.home') }}" class="inline-flex items-center gap-3 font-black">
                    <span class="grid h-10 w-10 place-items-center rounded-lg bg-teal-700 text-white">IR</span>
                    <span>ImmoRadar</span>
                </a>
                <a href="{{ route('marketing.trust') }}" class="ir-action-secondary">Confidentialité</a>
            </div>
        </header>

        <main class="mx-auto max-w-6xl px-4 py-12 sm:px-6 lg:px-8">
            <a href="{{ route('marketing.home') }}" class="text-sm font-black text-teal-700 underline decoration-teal-200 underline-offset-4">Retour à ImmoRadar</a>

            <section class="mt-8 grid gap-8 lg:grid-cols-[0.95fr_1.05fr]">
                <div>
                    <p class="text-sm font-black uppercase text-teal-700">Exemple complet - données de démonstration</p>
                    <h1 class="mt-3 text-4xl font-black leading-tight text-slate-950 md:text-5xl">Pourquoi ImmoRadar dit : à sécuriser</h1>
                    <p class="mt-5 text-lg leading-8 text-slate-700">
                        La Maison Montoison est un cas fictif issu des données de démo. Elle donne envie : budget proche, surface confortable, bonne projection. Le rapport évite pourtant le feu vert trop rapide, car plusieurs preuves restent absentes avant une offre.
                    </p>
                    <div class="mt-6 flex flex-wrap gap-3">
                        @if(config('app.demo_login_enabled'))
                            <form method="POST" action="{{ route('login.demo') }}">
                                @csrf
                                <button type="submit" class="ir-action-primary">Tester la démo guidée</button>
                            </form>
                        @endif
                        <a href="{{ url('/guides/documents-achat-immobilier') }}" class="ir-action-secondary">Voir les documents à demander</a>
                    </div>
                </div>

                <aside class="ir-panel p-5">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-xs font-black uppercase text-slate-500">Synthèse de démonstration</p>
                            <h2 class="mt-1 text-2xl font-black text-slate-950">Maison Montoison</h2>
                        </div>
                        <span class="rounded-full bg-amber-100 px-3 py-1 text-sm font-black text-amber-900">à sécuriser</span>
                    </div>
                    <div class="mt-5 grid gap-3 sm:grid-cols-3">
                        <div class="rounded-lg border border-teal-100 bg-teal-50 p-4">
                            <p class="text-xs font-black uppercase text-teal-700">Compatibilité</p>
                            <strong class="mt-1 block text-3xl text-teal-950">78</strong>
                        </div>
                        <div class="rounded-lg border border-slate-200 bg-slate-100 p-4">
                            <p class="text-xs font-black uppercase text-slate-500">Coût réel</p>
                            <strong class="mt-1 block text-3xl text-slate-950">986 €</strong>
                        </div>
                        <div class="rounded-lg border border-rose-100 bg-rose-50 p-4">
                            <p class="text-xs font-black uppercase text-rose-700">Vigilance</p>
                            <strong class="mt-1 block text-3xl text-rose-950">42</strong>
                        </div>
                    </div>
                </aside>
            </section>

            <section class="mt-10 grid gap-4 md:grid-cols-3">
                <article class="ir-panel p-5">
                    <p class="text-sm font-black uppercase text-slate-500">Données saisies</p>
                    <ul class="mt-3 space-y-2 text-sm leading-6 text-slate-700">
                        <li>Prix affiché : 238 000 €</li>
                        <li>Surface : 94 m²</li>
                        <li>Apport : 38 000 €</li>
                        <li>DPE : E, à relire avec les travaux</li>
                    </ul>
                </article>
                <article class="ir-panel p-5">
                    <p class="text-sm font-black uppercase text-amber-700">Ce qui bloque le feu vert</p>
                    <ul class="mt-3 space-y-2 text-sm leading-6 text-slate-700">
                        <li>Charges et taxe foncière non confirmées.</li>
                        <li>Travaux énergétiques à chiffrer.</li>
                        <li>Comparaison incomplète avec deux alternatives.</li>
                    </ul>
                </article>
                <article class="ir-panel border-teal-200 bg-teal-50/70 p-5">
                    <p class="text-sm font-black uppercase text-teal-700">Action prioritaire</p>
                    <p class="mt-3 text-lg font-black text-slate-950">Obtenir les preuves avant de négocier.</p>
                    <ul class="mt-3 space-y-2 text-sm leading-6 text-slate-700">
                        <li>Demander diagnostics et audit énergétique si nécessaire.</li>
                        <li>Revenir 24 h après visite avec ressenti à froid.</li>
                        <li>Préparer une négociation basée sur preuves.</li>
                    </ul>
                </article>
            </section>

            <section class="mt-10 rounded-lg border border-amber-200 bg-amber-50 p-6">
                <h2 class="text-2xl font-black text-amber-950">La décision n'est pas "non". Elle est "sécuriser avant offre".</h2>
                <p class="mt-3 leading-7 text-amber-950">C'est le cœur d'ImmoRadar : ne pas casser l'envie, mais empêcher une décision chère avec trop de zones floues.</p>
            </section>
        </main>
    </body>
</html>
