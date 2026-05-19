<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">ImmoRadar</h1>
                <p class="mt-1 text-sm text-slate-600">Compare tes biens, prépare tes visites et décide plus calmement.</p>
            </div>
            <a href="{{ route('projects.create') }}" class="rounded-md bg-emerald-700 px-4 py-2 text-sm font-semibold text-white">Nouveau projet</a>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        @if(session('status'))
            <div class="mb-4 rounded-md bg-emerald-50 p-4 text-sm text-emerald-800">{{ session('status') }}</div>
        @endif

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @forelse($projects as $project)
                <a href="{{ route('projects.show', $project) }}" class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h2 class="text-lg font-semibold text-slate-900">{{ $project->name }}</h2>
                            <p class="mt-1 text-sm capitalize text-slate-500">{{ $project->type }}</p>
                        </div>
                        <span class="rounded-md bg-slate-100 px-2 py-1 text-xs font-semibold text-slate-700">{{ $project->properties_count }} biens</span>
                    </div>
                    <dl class="mt-5 grid grid-cols-2 gap-3 text-sm">
                        <div>
                            <dt class="text-slate-500">Budget</dt>
                            <dd class="font-semibold text-slate-900">{{ $project->max_budget ? number_format((float) $project->max_budget, 0, ',', ' ') . ' €' : 'À définir' }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-500">Mensualité cible</dt>
                            <dd class="font-semibold text-slate-900">{{ $project->target_monthly_cost ? number_format((float) $project->target_monthly_cost, 0, ',', ' ') . ' €/mois' : 'À définir' }}</dd>
                        </div>
                    </dl>
                </a>
            @empty
                <div class="rounded-lg border border-dashed border-slate-300 bg-white p-8 text-center md:col-span-2 xl:col-span-3">
                    <h2 class="text-lg font-semibold text-slate-900">Commence par créer ton projet de recherche.</h2>
                    <p class="mt-2 text-slate-600">Un projet sert à comparer les biens avec les mêmes critères.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
