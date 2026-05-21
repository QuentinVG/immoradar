<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Confidentialité et limites | ImmoRadar</title>
        <meta name="description" content="ImmoRadar explique ses limites : calculs indicatifs, saisie manuelle, aucune connexion bancaire, aucun scraping et données de visite privées.">
        <meta name="robots" content="index, follow">
        <link rel="canonical" href="{{ route('marketing.trust') }}">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-slate-50 font-sans text-slate-900 antialiased">
        <header class="border-b border-slate-200 bg-white">
            <div class="mx-auto flex max-w-5xl items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
                <a href="{{ route('marketing.home') }}" class="inline-flex items-center gap-3 font-black">
                    <span class="grid h-10 w-10 place-items-center rounded-lg bg-teal-700 text-white">IR</span>
                    <span>ImmoRadar</span>
                </a>
                <a href="{{ route('marketing.example') }}" class="ir-action-secondary">Exemple</a>
            </div>
        </header>

        <main class="mx-auto max-w-5xl px-4 py-12 sm:px-6 lg:px-8">
            <a href="{{ route('marketing.home') }}" class="text-sm font-black text-teal-700 underline decoration-teal-200 underline-offset-4">Retour à ImmoRadar</a>
            <p class="mt-8 text-sm font-black uppercase text-teal-700">Confiance</p>
            <h1 class="mt-3 text-4xl font-black leading-tight text-slate-950 md:text-5xl">Confidentialité et limites</h1>
            <p class="mt-6 max-w-3xl text-lg leading-8 text-slate-700">ImmoRadar aide à préparer une décision immobilière. Il ne vend pas un feu vert automatique et ne remplace pas les professionnels de l'achat.</p>

            <div class="mt-10 grid gap-4 md:grid-cols-2">
                <section class="ir-panel p-6">
                    <h2 class="text-2xl font-black text-slate-950">Aucune connexion bancaire</h2>
                    <p class="mt-3 leading-7 text-slate-600">L'application ne demande pas l'accès à tes comptes, ne lit pas tes opérations et ne déclenche aucun paiement. Les montants sont saisis manuellement.</p>
                </section>
                <section class="ir-panel p-6">
                    <h2 class="text-2xl font-black text-slate-950">Aucun scraping d'annonces</h2>
                    <p class="mt-3 leading-7 text-slate-600">Les biens sont ajoutés à la main. C'est volontaire : le produit sert à comparer et sécuriser, pas à aspirer les portails immobiliers.</p>
                </section>
                <section class="ir-panel p-6">
                    <h2 class="text-2xl font-black text-slate-950">Calculs indicatifs</h2>
                    <p class="mt-3 leading-7 text-slate-600">Mensualité, frais, charges et coût réel mensuel restent des estimations. Ils doivent être confirmés avec une banque, un courtier, un notaire ou un professionnel.</p>
                </section>
                <section class="ir-panel p-6">
                    <h2 class="text-2xl font-black text-slate-950">Décision documentée</h2>
                    <p class="mt-3 leading-7 text-slate-600">Le verdict signale un niveau de vigilance et des informations manquantes. Il ne dit pas quoi acheter de façon absolue.</p>
                </section>
            </div>
        </main>
    </body>
</html>
