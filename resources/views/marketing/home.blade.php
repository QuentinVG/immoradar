<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>ImmoRadar - Rapport avant offre pour achat immobilier</title>
        <meta name="description" content="Compare 2 à 5 biens avant offre, estime le coût réel mensuel et repère les documents, risques et questions à sécuriser avant d'acheter.">
        <meta name="robots" content="index, follow">
        <link rel="canonical" href="{{ url('/') }}">
        <meta property="og:type" content="website">
        <meta property="og:locale" content="fr_FR">
        <meta property="og:title" content="ImmoRadar - Rapport avant offre immobilier">
        <meta property="og:description" content="Un dossier de décision pour acheteurs de résidence principale : coût réel, risques, documents et comparaison avant offre.">
        <meta property="og:url" content="{{ url('/') }}">
        <meta name="twitter:card" content="summary">
        <script type="application/ld+json">
            @verbatim
            {
                "@context": "https://schema.org",
                "@type": "SoftwareApplication",
                "name": "ImmoRadar",
                "applicationCategory": "FinanceApplication",
                "operatingSystem": "Web",
                "description": "Rapport avant offre pour comparer des biens immobiliers, estimer le coût réel mensuel et repérer les risques à sécuriser."
            }
            @endverbatim
        </script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-slate-50 font-sans text-slate-900 antialiased">
        <header class="border-b border-white/70 bg-white/90 shadow-sm shadow-slate-900/5 backdrop-blur">
            <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
                <a href="{{ route('marketing.home') }}" class="inline-flex items-center gap-3 font-black">
                    <span class="grid h-10 w-10 place-items-center rounded-lg bg-gradient-to-br from-teal-700 to-emerald-600 text-white shadow-lg shadow-teal-900/20">IR</span>
                    <span>ImmoRadar</span>
                </a>
                <nav class="flex items-center gap-2">
                    <a href="{{ route('marketing.example') }}" class="ir-action-secondary hidden sm:inline-flex">Voir un exemple</a>
                    @if(config('app.demo_login_enabled'))
                        <form method="POST" action="{{ route('login.demo') }}">
                            @csrf
                            <button type="submit" class="ir-action-primary">Tester la démo guidée</button>
                        </form>
                    @else
                        <a href="{{ route('register') }}" class="ir-action-primary">Créer un espace</a>
                    @endif
                </nav>
            </div>
        </header>

        <main>
            <section class="bg-gradient-to-br from-teal-50 via-white to-amber-50">
                <div class="mx-auto grid max-w-7xl items-center gap-10 px-4 py-12 sm:px-6 lg:grid-cols-[1fr_0.95fr] lg:px-8 lg:py-16">
                    <div>
                        <p class="text-sm font-black uppercase text-teal-700">Comparer 2 à 5 biens avant offre</p>
                        <h1 class="mt-4 max-w-4xl text-4xl font-black leading-tight text-slate-950 md:text-6xl">Le rapport d'achat qui refroidit le coup de coeur avant de signer.</h1>
                        <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-700">
                            ImmoRadar transforme une visite immobilière en dossier de décision : coût réel mensuel, risques DPE/travaux/charges, documents manquants, questions à poser et comparaison claire entre biens.
                        </p>
                        <div class="mt-8 flex flex-wrap gap-3">
                            @if(config('app.demo_login_enabled'))
                                <form method="POST" action="{{ route('login.demo') }}">
                                    @csrf
                                    <button type="submit" class="ir-action-primary">Tester la démo guidée</button>
                                </form>
                            @endif
                            <a href="{{ route('marketing.example') }}" class="ir-action-secondary">Inspecter l'exemple complet</a>
                            <a href="{{ route('marketing.trust') }}" class="ir-action-secondary">Confidentialité</a>
                        </div>
                        <dl class="mt-8 grid max-w-2xl gap-3 sm:grid-cols-3">
                            <div class="rounded-lg border border-teal-100 bg-white p-4">
                                <dt class="text-xs font-black uppercase text-slate-500">Entrée rapide</dt>
                                <dd class="mt-1 text-2xl font-black text-teal-800">6 infos</dd>
                                <p class="mt-1 text-sm text-slate-600">prix, surface, ville, DPE, charges, apport</p>
                            </div>
                            <div class="rounded-lg border border-amber-100 bg-white p-4">
                                <dt class="text-xs font-black uppercase text-slate-500">Sortie utile</dt>
                                <dd class="mt-1 text-2xl font-black text-amber-800">Avant offre</dd>
                                <p class="mt-1 text-sm text-slate-600">coût, risques, documents, questions</p>
                            </div>
                            <div class="rounded-lg border border-rose-100 bg-white p-4">
                                <dt class="text-xs font-black uppercase text-slate-500">Garde-fou</dt>
                                <dd class="mt-1 text-2xl font-black text-rose-800">Vigilance</dd>
                                <p class="mt-1 text-sm text-slate-600">un bien séduisant peut rester fragile</p>
                            </div>
                        </dl>
                    </div>

                    <aside class="ir-panel p-5 shadow-xl shadow-teal-900/10">
                        <div class="flex items-start justify-between gap-4 border-b border-slate-100 pb-4">
                            <div>
                                <p class="text-xs font-black uppercase text-slate-500">Exemple inspectable</p>
                                <h2 class="mt-1 text-2xl font-black text-slate-950">Maison Montoison</h2>
                            </div>
                            <span class="rounded-full bg-amber-100 px-3 py-1 text-sm font-black text-amber-900">à sécuriser</span>
                        </div>

                        <div class="mt-5 grid gap-3 sm:grid-cols-3">
                            <div class="rounded-lg border border-teal-100 bg-teal-50 p-4 shadow-sm">
                                <span class="text-xs font-black uppercase text-teal-700">Compatibilité</span>
                                <strong class="mt-1 block text-3xl text-teal-950">78</strong>
                                <p class="text-sm text-teal-900">bon compromis</p>
                            </div>
                            <div class="rounded-lg border border-slate-200 bg-slate-100 p-4 shadow-sm">
                                <span class="text-xs font-black uppercase text-slate-500">Coût réel</span>
                                <strong class="mt-1 block text-3xl text-slate-950">986 €</strong>
                                <p class="text-sm text-slate-600">par mois estimé</p>
                            </div>
                            <div class="rounded-lg border border-rose-100 bg-rose-50 p-4 shadow-sm">
                                <span class="text-xs font-black uppercase text-rose-700">Vigilance</span>
                                <strong class="mt-1 block text-3xl text-rose-950">42</strong>
                                <p class="text-sm text-rose-900">à vérifier</p>
                            </div>
                        </div>

                        <div class="mt-5 rounded-lg border border-amber-200 bg-amber-50 p-4">
                            <p class="font-black text-amber-950">Pourquoi ce n'est pas encore un feu vert</p>
                            <ul class="mt-3 space-y-2 text-sm leading-6 text-amber-950">
                                <li>Charges et taxe foncière à confirmer.</li>
                                <li>Travaux et DPE à relire avant négociation.</li>
                                <li>Comparer avec au moins deux alternatives.</li>
                            </ul>
                        </div>

                        <a href="{{ route('marketing.example') }}" class="ir-action-primary mt-5 w-full">Voir le dossier Maison Montoison</a>
                        <p class="mt-4 text-sm font-semibold text-slate-500">Saisie manuelle, pas de scraping et aucune donnée bancaire. Les calculs restent indicatifs.</p>
                    </aside>
                </div>
            </section>

            <section class="bg-white">
                <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
                    <div class="max-w-3xl">
                        <p class="text-sm font-black uppercase text-teal-700">Positionnement clair</p>
                        <h2 class="mt-2 text-3xl font-black text-slate-950">Pour les acheteurs de résidence principale qui hésitent entre plusieurs biens.</h2>
                        <p class="mt-3 text-slate-600">Le produit ne remplace pas SeLoger, un courtier ou un notaire. Il rassemble ce que l'acheteur oublie souvent après une visite : budget complet, documents, risques et arbitrages.</p>
                    </div>
                    <div class="mt-8 grid gap-4 md:grid-cols-4">
                        <article class="ir-panel p-5">
                            <h3 class="font-black text-slate-950">1. Saisir l'essentiel</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-600">Prix, surface, DPE, charges, taxe, trajet et critères non négociables.</p>
                        </article>
                        <article class="ir-panel p-5">
                            <h3 class="font-black text-slate-950">2. Visiter sans oublier</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-600">Checklist mobile sur bruit, humidité, fenêtres, communs, stationnement et ressenti à froid.</p>
                        </article>
                        <article class="ir-panel p-5">
                            <h3 class="font-black text-slate-950">3. Sécuriser l'offre</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-600">Documents à demander, points bloquants, niveau de preuve et conditions à prévoir.</p>
                        </article>
                        <article class="ir-panel p-5">
                            <h3 class="font-black text-slate-950">4. Comparer froidement</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-600">Même grille pour tous les biens : coût, projection, vigilance, confiance.</p>
                        </article>
                    </div>
                </div>
            </section>

            <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
                <div class="grid gap-5 lg:grid-cols-[0.9fr_1.1fr]">
                    <div>
                        <p class="text-sm font-black uppercase text-teal-700">Ce qui différencie</p>
                        <h2 class="mt-2 text-3xl font-black text-slate-950">Un dossier de décision, pas un simple simulateur.</h2>
                        <p class="mt-4 leading-7 text-slate-600">La valeur n'est pas seulement un score. C'est la liste des informations qui manquent avant de parler prix, négociation ou compromis.</p>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div class="ir-panel p-5">
                            <h3 class="font-black text-slate-950">Questions à poser</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-600">Charges exactes, taxe foncière, travaux votés, audit énergétique, servitudes, bruit, humidité.</p>
                        </div>
                        <div class="ir-panel p-5">
                            <h3 class="font-black text-slate-950">Arguments de négociation</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-600">DPE faible, travaux chiffrés, charges floues, documents absents ou comparaison défavorable.</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
                <div class="max-w-3xl">
                    <p class="text-sm font-black uppercase text-teal-700">Guides utiles</p>
                    <h2 class="mt-2 text-3xl font-black text-slate-950">Des pages publiques pour répondre aux vraies questions avant une offre.</h2>
                </div>
                <div class="mt-8 grid gap-4 md:grid-cols-4">
                    <a href="{{ url('/guides/checklist-visite-immobiliere') }}" class="ir-panel bg-white p-5 transition hover:border-teal-300">
                        <h3 class="font-black text-slate-950">Checklist visite immobilière</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">Quartier, intérieur, technique, budget et ressenti à vérifier.</p>
                    </a>
                    <a href="{{ url('/guides/cout-reel-mensuel-immobilier') }}" class="ir-panel bg-white p-5 transition hover:border-teal-300">
                        <h3 class="font-black text-slate-950">Coût réel mensuel</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">Crédit, charges, taxe, énergie, assurance et travaux.</p>
                    </a>
                    <a href="{{ url('/guides/comparer-biens-immobiliers') }}" class="ir-panel bg-white p-5 transition hover:border-teal-300">
                        <h3 class="font-black text-slate-950">Comparer plusieurs biens</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">Séparer envie, budget, risques et informations manquantes.</p>
                    </a>
                    <a href="{{ url('/guides/documents-achat-immobilier') }}" class="ir-panel bg-white p-5 transition hover:border-teal-300">
                        <h3 class="font-black text-slate-950">Documents à demander</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">Diagnostics, audit énergétique, PV d'AG et travaux votés.</p>
                    </a>
                </div>
            </section>

            <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
                <div class="grid gap-5 lg:grid-cols-2">
                    <div class="ir-panel p-6">
                        <p class="text-sm font-black uppercase text-teal-700">Confiance</p>
                        <h2 class="mt-2 text-3xl font-black text-slate-950">Aucune connexion bancaire, aucune annonce aspirée.</h2>
                        <p class="mt-4 leading-7 text-slate-600">Tu ajoutes tes biens manuellement. C'est moins magique qu'une app connectée, mais plus clair, plus sobre et suffisant pour préparer une offre.</p>
                        <a href="{{ route('marketing.trust') }}" class="ir-action-secondary mt-5">Lire les limites</a>
                    </div>
                    <div class="ir-panel p-6">
                        <p class="text-sm font-black uppercase text-rose-700">Limites assumées</p>
                        <h2 class="mt-2 text-3xl font-black text-slate-950">Les calculs restent indicatifs.</h2>
                        <p class="mt-4 leading-7 text-slate-600">L'estimation financière doit être confirmée avec une banque, un courtier, un notaire ou un professionnel. ImmoRadar aide à décider, il ne remplace pas un avis expert.</p>
                    </div>
                </div>
            </section>
        </main>
    </body>
</html>
