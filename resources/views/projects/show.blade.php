<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="text-sm font-black uppercase text-teal-700">Projet</p>
                <h1 class="mt-1 text-3xl font-black text-slate-950">{{ $project->name }}</h1>
                <p class="mt-1 text-sm text-slate-600">Le but : voir rapidement le meilleur candidat, le risque principal et ce qu’il reste à vérifier.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('projects.properties.create', $project) }}" class="ir-action-primary">Ajouter un bien</a>
                <a href="{{ route('projects.compare', $project) }}" class="ir-action-secondary">Comparer</a>
                <a href="{{ route('projects.report', $project) }}" class="ir-action-secondary">PDF projet</a>
            </div>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-8 px-4 py-8 sm:px-6 lg:px-8">
        @if(session('status'))
            <div class="rounded-md border border-teal-200 bg-teal-50 p-4 text-sm font-semibold text-teal-900">{{ session('status') }}</div>
        @endif

        <section class="grid gap-4 md:grid-cols-4">
            <div class="ir-mini-card">
                <p class="text-sm text-slate-500">Biens suivis</p>
                <p class="mt-2 text-3xl font-black text-slate-950">{{ $summary['properties_count'] }}</p>
            </div>
            <div class="ir-mini-card">
                <p class="text-sm text-slate-500">Coût moyen</p>
                <p class="mt-2 text-3xl font-black text-slate-950">{{ $summary['average_monthly_cost'] ? number_format($summary['average_monthly_cost'], 0, ',', ' ') . ' €' : '—' }}</p>
            </div>
            <div class="ir-mini-card border-amber-200 bg-amber-50/80">
                <p class="text-sm text-slate-500">Infos à compléter</p>
                <p class="mt-2 text-3xl font-black text-slate-950">{{ $summary['missing_information_count'] }}</p>
            </div>
            <div class="ir-mini-card">
                <p class="text-sm text-slate-500">Budget max</p>
                <p class="mt-2 text-3xl font-black text-slate-950">{{ $project->max_budget ? number_format((float) $project->max_budget, 0, ',', ' ') . ' €' : '—' }}</p>
            </div>
        </section>

        <section class="ir-hero-band p-6 sm:p-8">
            <div class="grid gap-6 lg:grid-cols-[0.8fr_1.2fr] lg:items-center">
                <div>
                    <p class="text-sm font-black uppercase text-amber-200">Niveau de décision</p>
                    <h2 class="mt-2 text-3xl font-black text-white">{{ $summary['decision_readiness']['label'] }}</h2>
                    <p class="mt-3 text-6xl font-black text-teal-200">{{ $summary['decision_readiness']['score'] }}/100</p>
                    <div class="mt-4 ir-scorebar bg-white/15">
                        <div class="ir-scorebar-fill" style="width: {{ $summary['decision_readiness']['score'] }}%"></div>
                    </div>
                </div>
                <div class="grid gap-3">
                    <div class="ir-glass p-4">
                        <span class="text-sm font-semibold text-slate-200">Checklists remplies</span>
                        <strong class="mt-1 block text-2xl text-white">{{ $summary['decision_readiness']['checklist_progress'] }}%</strong>
                    </div>
                    <div class="ir-glass p-4">
                        <span class="text-sm font-semibold text-slate-200">À faire avant de trancher</span>
                        <ul class="mt-2 space-y-1 text-sm text-slate-100">
                            @foreach($summary['decision_readiness']['actions'] as $action)
                                <li>{{ $action }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <section class="grid gap-4 lg:grid-cols-2">
            <div class="ir-panel border-teal-200 bg-teal-50/70 p-6">
                <p class="text-sm font-black uppercase text-teal-700">Meilleur choix actuel</p>
                @if($summary['best_property'])
                    <h2 class="mt-2 text-2xl font-black text-slate-950">{{ $summary['best_property']['property']->title }}</h2>
                    <p class="mt-2 text-slate-600">{{ $summary['best_property']['verdict'] }}</p>
                    <p class="mt-4 text-4xl font-black text-teal-800">{{ $summary['best_property']['compatibility'] }}/100</p>
                @else
                    <p class="mt-3 text-slate-600">Ajoute au moins un bien pour obtenir un classement.</p>
                @endif
            </div>
            <div class="ir-panel border-rose-200 bg-rose-50/75 p-6">
                <p class="text-sm font-black uppercase text-rose-700">Coup de cœur risqué</p>
                @if($summary['risky_crush'])
                    <h2 class="mt-2 text-2xl font-black text-slate-950">{{ $summary['risky_crush']['property']->title }}</h2>
                    <p class="mt-2 text-slate-600">Projection {{ $summary['risky_crush']['projection'] }}/100, vigilance {{ $summary['risky_crush']['vigilance'] }}/100.</p>
                @else
                    <p class="mt-3 text-slate-600">Aucun coup de cœur risqué détecté pour l’instant.</p>
                @endif
            </div>
        </section>

        <section class="ir-panel p-5">
            <div class="flex items-center justify-between gap-3">
                <h2 class="text-lg font-black text-slate-950">Top biens</h2>
                <a href="{{ route('projects.properties.index', $project) }}" class="text-sm font-black text-teal-700">Voir tous les biens</a>
            </div>
            <div class="mt-4 grid gap-3 md:grid-cols-3">
                @forelse($summary['top_properties'] as $card)
                    <a href="{{ route('projects.properties.show', [$project, $card['property']]) }}" class="rounded-md border border-slate-200 bg-white p-4 hover:border-teal-300">
                        <h3 class="font-black text-slate-950">{{ $card['property']->title }}</h3>
                        <p class="mt-1 text-sm text-slate-500">{{ $card['property']->city }} · {{ $card['verdict'] }}</p>
                        <div class="mt-3 flex items-center justify-between text-sm">
                            <span>Compatibilité</span>
                            <strong>{{ $card['compatibility'] }}/100</strong>
                        </div>
                    </a>
                @empty
                    <p class="text-slate-600">Aucun bien ajouté.</p>
                @endforelse
            </div>
        </section>

        <section class="ir-panel p-5">
            <h2 class="text-lg font-black text-slate-950">Alertes principales</h2>
            <div class="mt-4 grid gap-3">
                @forelse($summary['main_alerts'] as $alert)
                    <div class="rounded-md border border-slate-200 p-3">
                        <p class="font-semibold text-slate-900">{{ $alert->title }}</p>
                        <p class="text-sm text-slate-600">{{ $alert->message }}</p>
                    </div>
                @empty
                    <p class="text-slate-600">Pas d’alerte majeure pour l’instant.</p>
                @endforelse
            </div>
        </section>
    </div>
</x-app-layout>
