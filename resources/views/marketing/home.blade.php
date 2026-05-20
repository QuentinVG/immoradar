<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>ImmoRadar - Checklist visite et comparaison immobilière</title>
        <meta name="description" content="ImmoRadar aide à comparer des biens immobiliers, préparer une checklist de visite, estimer le coût réel mensuel et repérer les alertes avant une offre.">
        <meta name="robots" content="index, follow">
        <link rel="canonical" href="{{ url('/') }}">
        <meta property="og:type" content="website">
        <meta property="og:locale" content="fr_FR">
        <meta property="og:title" content="ImmoRadar - Assistant de visite immobilière">
        <meta property="og:description" content="Checklist visite, coût réel mensuel, alertes et comparaison de biens pour décider plus calmement.">
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
                "description": "Assistant de visite immobilière pour comparer des biens, estimer le coût réel mensuel et repérer les alertes avant une offre."
            }
            @endverbatim
        </script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-slate-50 font-sans text-slate-900 antialiased">
        <header class="border-b border-slate-200 bg-white">
            <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
                <a href="{{ route('marketing.home') }}" class="inline-flex items-center gap-3 font-black">
                    <span class="grid h-10 w-10 place-items-center rounded-lg bg-teal-700 text-white">IR</span>
                    <span>ImmoRadar</span>
                </a>
                <nav class="flex items-center gap-2">
                    <a href="{{ route('login') }}" class="ir-action-secondary">Connexion</a>
                    <a href="{{ route('register') }}" class="ir-action-primary hidden sm:inline-flex">Créer un compte</a>
                </nav>
            </div>
        </header>

        <main>
            <section class="bg-[#f3f7f3]">
                <div class="mx-auto grid max-w-7xl items-center gap-10 px-4 py-12 sm:px-6 lg:grid-cols-[1fr_0.95fr] lg:px-8 lg:py-16">
                    <div>
                        <p class="text-sm font-black uppercase text-teal-700">Checklist visite, coût réel, verdict</p>
                        <h1 class="mt-4 max-w-4xl text-4xl font-black leading-tight text-slate-950 md:text-6xl">Visite un bien sans te laisser embarquer par le coup de cœur.</h1>
                        <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-700">
                            ImmoRadar t'aide à comparer tes biens, préparer les questions de visite, estimer le coût réel mensuel et voir ce qui doit être vérifié avant une offre.
                        </p>
                        <div class="mt-8 flex flex-wrap gap-3">
                            <a href="{{ route('register') }}" class="ir-action-primary">Créer mon espace</a>
                            @if(config('app.demo_login_enabled'))
                                <form method="POST" action="{{ route('login.demo') }}">
                                    @csrf
                                    <button type="submit" class="ir-action-secondary">Tester le compte démo</button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="ir-action-secondary">Connexion</a>
                            @endif
                        </div>
                        <dl class="mt-8 grid max-w-2xl gap-3 sm:grid-cols-3">
                            <div class="rounded-lg border border-teal-100 bg-white p-4">
                                <dt class="text-xs font-black uppercase text-slate-500">Temps gagné</dt>
                                <dd class="mt-1 text-2xl font-black text-teal-800">10 min</dd>
                                <p class="mt-1 text-sm text-slate-600">pour relire un bien après visite</p>
                            </div>
                            <div class="rounded-lg border border-amber-100 bg-white p-4">
                                <dt class="text-xs font-black uppercase text-slate-500">Focus</dt>
                                <dd class="mt-1 text-2xl font-black text-amber-800">Budget</dd>
                                <p class="mt-1 text-sm text-slate-600">charges, taxe, énergie, travaux</p>
                            </div>
                            <div class="rounded-lg border border-rose-100 bg-white p-4">
                                <dt class="text-xs font-black uppercase text-slate-500">Garde-fou</dt>
                                <dd class="mt-1 text-2xl font-black text-rose-800">Alertes</dd>
                                <p class="mt-1 text-sm text-slate-600">DPE, trajet, infos manquantes</p>
                            </div>
                        </dl>
                    </div>

                    <aside class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="flex items-start justify-between gap-4 border-b border-slate-100 pb-4">
                            <div>
                                <p class="text-xs font-black uppercase text-slate-500">Exemple de synthèse</p>
                                <h2 class="mt-1 text-2xl font-black text-slate-950">Maison Montoison</h2>
                            </div>
                            <span class="rounded-full bg-amber-100 px-3 py-1 text-sm font-black text-amber-900">à sécuriser</span>
                        </div>

                        <div class="mt-5 grid gap-3 sm:grid-cols-3">
                            <div class="rounded-lg bg-teal-50 p-4">
                                <span class="text-xs font-black uppercase text-teal-700">Compatibilité</span>
                                <strong class="mt-1 block text-3xl text-teal-950">78</strong>
                                <p class="text-sm text-teal-900">bon compromis</p>
                            </div>
                            <div class="rounded-lg bg-slate-100 p-4">
                                <span class="text-xs font-black uppercase text-slate-500">Coût réel</span>
                                <strong class="mt-1 block text-3xl text-slate-950">986 €</strong>
                                <p class="text-sm text-slate-600">par mois estimé</p>
                            </div>
                            <div class="rounded-lg bg-rose-50 p-4">
                                <span class="text-xs font-black uppercase text-rose-700">Vigilance</span>
                                <strong class="mt-1 block text-3xl text-rose-950">42</strong>
                                <p class="text-sm text-rose-900">à vérifier</p>
                            </div>
                        </div>

                        <div class="mt-5 rounded-lg border border-amber-200 bg-amber-50 p-4">
                            <p class="font-black text-amber-950">Avant de décider</p>
                            <ul class="mt-3 space-y-2 text-sm leading-6 text-amber-950">
                                <li>Confirmer les charges et la taxe foncière.</li>
                                <li>Revoir le ressenti à froid après la visite.</li>
                                <li>Comparer avec au moins deux alternatives.</li>
                            </ul>
                        </div>

                        <p class="mt-4 text-sm font-semibold text-slate-500">Pas d'IA, pas de scraping, pas d'annonce importée automatiquement. Les biens sont ajoutés à la main.</p>
                    </aside>
                </div>
            </section>

            <section class="bg-white">
                <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
                    <div class="max-w-3xl">
                        <p class="text-sm font-black uppercase text-teal-700">Parcours simple</p>
                        <h2 class="mt-2 text-3xl font-black text-slate-950">L'app doit rester compréhensible en moins de 30 secondes.</h2>
                        <p class="mt-3 text-slate-600">Le but n'est pas de remplir un dossier administratif. Le but est de savoir quoi vérifier et quoi comparer.</p>
                    </div>
                    <div class="mt-8 grid gap-4 md:grid-cols-4">
                        <article class="ir-panel p-5">
                            <h3 class="font-black text-slate-950">1. Projet</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-600">Budget, trajet, surface, critères importants.</p>
                        </article>
                        <article class="ir-panel p-5">
                            <h3 class="font-black text-slate-950">2. Biens</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-600">Ajout manuel, avec les infos vraiment utiles.</p>
                        </article>
                        <article class="ir-panel p-5">
                            <h3 class="font-black text-slate-950">3. Visite</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-600">Checklist mobile, réponses rapides, sauvegarde automatique.</p>
                        </article>
                        <article class="ir-panel p-5">
                            <h3 class="font-black text-slate-950">4. Décision</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-600">Scores expliqués, alertes, comparaison, rapport PDF.</p>
                        </article>
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
                        <p class="mt-2 text-sm leading-6 text-slate-600">Diagnostics, audit énergétique, PV d’AG et travaux votés.</p>
                    </a>
                </div>
            </section>

            <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
                <div class="grid gap-5 lg:grid-cols-2">
                    <div class="ir-panel p-6">
                        <p class="text-sm font-black uppercase text-teal-700">Ce que ça règle</p>
                        <h2 class="mt-2 text-3xl font-black text-slate-950">Tu peux aimer un bien, mais tu vois aussi ce qui peut te coûter cher.</h2>
                        <p class="mt-4 leading-7 text-slate-600">L'application remet le budget, les travaux, le trajet, le DPE et les informations manquantes au même niveau que le ressenti.</p>
                    </div>
                    <div class="ir-panel p-6">
                        <p class="text-sm font-black uppercase text-rose-700">Limites assumées</p>
                        <h2 class="mt-2 text-3xl font-black text-slate-950">Les calculs restent indicatifs.</h2>
                        <p class="mt-4 leading-7 text-slate-600">L'estimation financière doit être confirmée avec une banque, un courtier ou un professionnel. ImmoRadar aide à décider, il ne remplace pas un avis expert.</p>
                    </div>
                </div>
            </section>
        </main>
    </body>
</html>
