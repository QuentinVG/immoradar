<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="text-sm font-black uppercase text-teal-700">Tableau de bord</p>
                <h1 class="mt-1 text-3xl font-black text-slate-950">Tes recherches immobilières</h1>
                <p class="mt-1 text-sm text-slate-600">Ajoute un projet, compare les biens, puis garde seulement ceux qui méritent une visite.</p>
            </div>
            <a href="{{ route('projects.create') }}" class="ir-action-primary">Nouveau projet</a>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
        @if(session('status'))
            <div class="rounded-md border border-teal-200 bg-teal-50 p-4 text-sm font-semibold text-teal-900">{{ session('status') }}</div>
        @endif

        <section class="grid gap-4 lg:grid-cols-[1.15fr_0.85fr]">
            <div class="ir-panel overflow-hidden bg-slate-950 p-6 text-white">
                <p class="text-sm font-black uppercase text-amber-300">Parcours rapide</p>
                <h2 class="mt-2 text-2xl font-black">1 projet, 3 biens, 1 checklist, une décision plus calme.</h2>
                <div class="mt-5 grid gap-3 text-sm sm:grid-cols-3">
                    <span class="rounded-lg border border-white/10 bg-white/10 p-3">Coût réel</span>
                    <span class="rounded-lg border border-white/10 bg-white/10 p-3">Alertes</span>
                    <span class="rounded-lg border border-white/10 bg-white/10 p-3">Verdict</span>
                </div>
            </div>
            <div class="ir-soft-panel p-6">
                <p class="text-sm font-black uppercase text-rose-700">Rappel utile</p>
                <h2 class="mt-2 text-xl font-black text-slate-950">Le coup de cœur vient après les vérifications.</h2>
                <p class="mt-2 text-sm leading-6 text-slate-600">ImmoRadar met les risques avant la décision : budget, DPE, trajet, travaux, charges, infos manquantes.</p>
            </div>
        </section>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @forelse($projects as $project)
                <a href="{{ route('projects.show', $project) }}" class="ir-panel group p-5 transition hover:-translate-y-0.5 hover:border-teal-300 hover:shadow-lg hover:shadow-teal-900/10">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h2 class="text-lg font-black text-slate-950 group-hover:text-teal-800">{{ $project->name }}</h2>
                            <p class="mt-1 text-sm capitalize text-slate-500">{{ $project->type }}</p>
                        </div>
                        <span class="rounded-md bg-teal-50 px-2 py-1 text-xs font-black text-teal-800">{{ $project->properties_count }} biens</span>
                    </div>
                    <dl class="mt-5 grid grid-cols-2 gap-3 text-sm">
                        <div class="rounded-md bg-slate-50 p-3">
                            <dt class="text-slate-500">Budget</dt>
                            <dd class="font-black text-slate-950">{{ $project->max_budget ? number_format((float) $project->max_budget, 0, ',', ' ') . ' €' : 'À définir' }}</dd>
                        </div>
                        <div class="rounded-md bg-amber-50 p-3">
                            <dt class="text-slate-500">Mensualité cible</dt>
                            <dd class="font-black text-slate-950">{{ $project->target_monthly_cost ? number_format((float) $project->target_monthly_cost, 0, ',', ' ') . ' €/mois' : 'À définir' }}</dd>
                        </div>
                    </dl>
                </a>
            @empty
                <div class="ir-panel border-dashed border-teal-300 p-8 md:col-span-2 xl:col-span-3">
                    <div class="mx-auto max-w-3xl text-center">
                        <p class="text-sm font-black uppercase text-teal-700">Premiers pas</p>
                        <h2 class="mt-2 text-2xl font-black text-slate-950">Crée ton projet, puis ajoute 2 ou 3 biens.</h2>
                        <p class="mt-2 text-slate-600">Tu n'as pas besoin de tout remplir tout de suite. Commence par budget, ville, surface et trajet.</p>
                        <a href="{{ route('projects.create') }}" class="ir-action-primary mt-5">Créer mon premier projet</a>
                    </div>
                    <div class="mt-8 grid gap-3 md:grid-cols-3">
                        <div class="rounded-lg bg-teal-50 p-4">
                            <strong class="text-slate-950">1. Projet</strong>
                            <p class="mt-1 text-sm text-slate-600">Budget, mensualité cible, critères importants.</p>
                        </div>
                        <div class="rounded-lg bg-amber-50 p-4">
                            <strong class="text-slate-950">2. Biens</strong>
                            <p class="mt-1 text-sm text-slate-600">Ajout manuel des biens à comparer, sans scraping.</p>
                        </div>
                        <div class="rounded-lg bg-rose-50 p-4">
                            <strong class="text-slate-950">3. Visite</strong>
                            <p class="mt-1 text-sm text-slate-600">Checklist mobile, alertes, verdict et PDF.</p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
