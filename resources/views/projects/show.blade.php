<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <p class="text-sm font-semibold uppercase text-emerald-700">Projet</p>
                <h1 class="text-2xl font-bold text-slate-900">{{ $project->name }}</h1>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('projects.properties.create', $project) }}" class="rounded-md bg-emerald-700 px-4 py-2 text-sm font-semibold text-white">Ajouter un bien</a>
                <a href="{{ route('projects.compare', $project) }}" class="rounded-md border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700">Comparer</a>
                <a href="{{ route('projects.report', $project) }}" class="rounded-md border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700">PDF projet</a>
            </div>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-8 px-4 py-8 sm:px-6 lg:px-8">
        @if(session('status'))
            <div class="rounded-md bg-emerald-50 p-4 text-sm text-emerald-800">{{ session('status') }}</div>
        @endif

        <section class="grid gap-4 md:grid-cols-4">
            <div class="rounded-lg bg-white p-5 shadow-sm">
                <p class="text-sm text-slate-500">Biens suivis</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ $summary['properties_count'] }}</p>
            </div>
            <div class="rounded-lg bg-white p-5 shadow-sm">
                <p class="text-sm text-slate-500">Coût moyen</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ $summary['average_monthly_cost'] ? number_format($summary['average_monthly_cost'], 0, ',', ' ') . ' €' : '—' }}</p>
            </div>
            <div class="rounded-lg bg-white p-5 shadow-sm">
                <p class="text-sm text-slate-500">Infos à compléter</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ $summary['missing_information_count'] }}</p>
            </div>
            <div class="rounded-lg bg-white p-5 shadow-sm">
                <p class="text-sm text-slate-500">Budget max</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ $project->max_budget ? number_format((float) $project->max_budget, 0, ',', ' ') . ' €' : '—' }}</p>
            </div>
        </section>

        <section class="grid gap-4 lg:grid-cols-2">
            <div class="rounded-lg border border-emerald-200 bg-white p-5">
                <p class="text-sm font-semibold uppercase text-emerald-700">Meilleur choix actuel</p>
                @if($summary['best_property'])
                    <h2 class="mt-2 text-xl font-bold text-slate-900">{{ $summary['best_property']['property']->title }}</h2>
                    <p class="mt-2 text-slate-600">{{ $summary['best_property']['verdict'] }}</p>
                    <p class="mt-4 text-3xl font-bold text-emerald-700">{{ $summary['best_property']['compatibility'] }}/100</p>
                @else
                    <p class="mt-3 text-slate-600">Ajoute au moins un bien pour obtenir un classement.</p>
                @endif
            </div>
            <div class="rounded-lg border border-amber-200 bg-white p-5">
                <p class="text-sm font-semibold uppercase text-amber-700">Coup de cœur risqué</p>
                @if($summary['risky_crush'])
                    <h2 class="mt-2 text-xl font-bold text-slate-900">{{ $summary['risky_crush']['property']->title }}</h2>
                    <p class="mt-2 text-slate-600">Projection {{ $summary['risky_crush']['projection'] }}/100, vigilance {{ $summary['risky_crush']['vigilance'] }}/100.</p>
                @else
                    <p class="mt-3 text-slate-600">Aucun coup de cœur risqué détecté pour l’instant.</p>
                @endif
            </div>
        </section>

        <section class="rounded-lg bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between gap-3">
                <h2 class="text-lg font-semibold text-slate-900">Top biens</h2>
                <a href="{{ route('projects.properties.index', $project) }}" class="text-sm font-semibold text-emerald-700">Voir tous les biens</a>
            </div>
            <div class="mt-4 grid gap-3 md:grid-cols-3">
                @forelse($summary['top_properties'] as $card)
                    <a href="{{ route('projects.properties.show', [$project, $card['property']]) }}" class="rounded-md border border-slate-200 p-4 hover:border-emerald-300">
                        <h3 class="font-semibold text-slate-900">{{ $card['property']->title }}</h3>
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

        <section class="rounded-lg bg-white p-5 shadow-sm">
            <h2 class="text-lg font-semibold text-slate-900">Alertes principales</h2>
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
