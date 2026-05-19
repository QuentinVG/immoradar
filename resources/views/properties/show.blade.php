<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <p class="text-sm font-black uppercase text-teal-700">{{ $property->city }}</p>
                <h1 class="mt-1 text-3xl font-black text-slate-950">{{ $property->title }}</h1>
                <p class="mt-1 text-sm text-slate-600">Lis d’abord le verdict, puis le coût et les alertes. Le détail vient après.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('projects.properties.visit', [$project, $property]) }}" class="ir-action-primary">Mode visite</a>
                <a href="{{ route('projects.properties.report', [$project, $property]) }}" class="ir-action-secondary">PDF</a>
                <a href="{{ route('projects.properties.edit', [$project, $property]) }}" class="ir-action-secondary">Modifier</a>
            </div>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
        @if(session('status'))
            <div class="rounded-md border border-teal-200 bg-teal-50 p-4 text-sm font-semibold text-teal-900">{{ session('status') }}</div>
        @endif

        <section class="grid gap-4 lg:grid-cols-[1.2fr_0.8fr]">
            <div class="ir-panel border-teal-200 bg-teal-50/80 p-6">
                <p class="text-sm font-black uppercase text-teal-700">Verdict</p>
                <h2 class="mt-2 text-4xl font-black text-slate-950">{{ $verdict['title'] }}</h2>
                <p class="mt-3 max-w-3xl text-base leading-7 text-slate-700">{{ $verdict['summary'] }}</p>
                <div class="mt-5 grid gap-4 md:grid-cols-2">
                    <div class="rounded-lg bg-white/75 p-4">
                        <h3 class="font-black text-slate-950">Points forts</h3>
                        <ul class="mt-2 space-y-1 text-sm text-slate-600">
                            @foreach($verdict['strengths'] as $item)<li>{{ $item }}</li>@endforeach
                        </ul>
                    </div>
                    <div class="rounded-lg bg-white/75 p-4">
                        <h3 class="font-black text-slate-950">À sécuriser</h3>
                        <ul class="mt-2 space-y-1 text-sm text-slate-600">
                            @foreach($verdict['watch_points'] as $item)<li>{{ $item }}</li>@endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="ir-panel overflow-hidden bg-white">
                @if($property->main_photo_path)
                    <img src="{{ Storage::url($property->main_photo_path) }}" alt="{{ $property->title }}" class="h-full min-h-64 w-full object-cover">
                @else
                    <div class="flex h-full min-h-64 items-center justify-center bg-gradient-to-br from-slate-100 via-teal-50 to-amber-50 text-sm font-semibold text-slate-500">Photo principale à ajouter</div>
                @endif
            </div>
        </section>

        <section class="ir-panel p-6">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-black uppercase text-slate-500">{{ $cost['is_partial'] ? 'Coût partiel estimé' : 'Coût réel estimé' }}</p>
                    <p class="mt-2 text-5xl font-black text-slate-950">{{ number_format($cost['real_monthly_cost'], 0, ',', ' ') }} €/mois</p>
                    <p class="mt-2 inline-flex rounded-md bg-teal-50 px-3 py-1 text-sm font-black text-teal-800">{{ $cost['budget_badge'] }}</p>
                </div>
                <p class="max-w-md rounded-md border border-amber-200 bg-amber-50 p-3 text-sm font-semibold leading-6 text-amber-950">{{ $cost['notice'] }}</p>
            </div>
            <dl class="mt-6 grid gap-3 md:grid-cols-4">
                @foreach($cost['details'] as $label => $amount)
                <div class="rounded-md border border-slate-100 bg-slate-50 p-3">
                        <dt class="text-xs uppercase text-slate-500">{{ str_replace('_', ' ', $label) }}</dt>
                        <dd class="mt-1 font-semibold text-slate-900">{{ number_format($amount, 0, ',', ' ') }} €</dd>
                    </div>
                @endforeach
            </dl>
            @if($cost['missing'])
                <p class="mt-4 text-sm text-slate-600">Infos manquantes : {{ implode(', ', $cost['missing']) }}.</p>
            @endif
        </section>

        <section class="grid gap-4 md:grid-cols-4">
            @foreach(['compatibility' => 'Compatibilité', 'solidity' => 'Solidité', 'projection' => 'Projection', 'vigilance' => 'Vigilance'] as $key => $label)
                <div class="ir-panel p-5">
                    <p class="text-sm text-slate-500">{{ $label }}</p>
                    <p class="mt-2 text-3xl font-black {{ $key === 'vigilance' ? 'text-rose-700' : 'text-slate-950' }}">{{ $scores[$key]['score'] }}/100</p>
                    <div class="mt-3 h-2 rounded-full bg-slate-100">
                        <div class="h-2 rounded-full {{ $key === 'vigilance' ? 'bg-rose-600' : 'bg-teal-700' }}" style="width: {{ $scores[$key]['score'] }}%"></div>
                    </div>
                    <ul class="mt-3 space-y-1 text-xs text-slate-600">
                        @foreach(array_slice($scores[$key]['reasons'], 0, 4) as $reason)
                            <li>{{ $reason }}</li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </section>

        <section class="ir-panel p-6">
            <h2 class="text-lg font-black text-slate-950">Alertes</h2>
            <div class="mt-4 grid gap-3">
                @forelse($property->alerts as $alert)
                    <div class="rounded-md border p-4 {{ $alert->severity === 'danger' ? 'border-red-200 bg-red-50' : 'border-amber-200 bg-amber-50' }}">
                        <p class="font-semibold text-slate-900">{{ $alert->title }}</p>
                        <p class="mt-1 text-sm text-slate-700">{{ $alert->message }}</p>
                    </div>
                @empty
                    <p class="text-slate-600">Pas d’alerte majeure détectée.</p>
                @endforelse
            </div>
        </section>

        <section class="grid gap-4 lg:grid-cols-2">
            <div class="ir-panel p-6">
                <h2 class="text-lg font-black text-slate-950">Prochaines actions</h2>
                <ul class="mt-3 space-y-2 text-sm text-slate-700">
                    @foreach($verdict['next_actions'] as $action)<li>{{ $action }}</li>@endforeach
                </ul>
            </div>
            <div class="ir-panel p-6">
                <h2 class="text-lg font-black text-slate-950">Détails</h2>
                <dl class="mt-3 grid grid-cols-2 gap-3 text-sm">
                    <div><dt class="text-slate-500">Prix</dt><dd class="font-semibold">{{ $property->price ? number_format((float) $property->price, 0, ',', ' ') . ' €' : '—' }}</dd></div>
                    <div><dt class="text-slate-500">Surface</dt><dd class="font-semibold">{{ $property->surface ? $property->surface . ' m²' : '—' }}</dd></div>
                    <div><dt class="text-slate-500">DPE</dt><dd class="font-semibold">{{ $property->dpe }}</dd></div>
                    <div><dt class="text-slate-500">Trajet</dt><dd class="font-semibold">{{ $property->commute_minutes ? $property->commute_minutes . ' min' : '—' }}</dd></div>
                    <div><dt class="text-slate-500">Confiance</dt><dd class="font-semibold">{{ $scores['confidence_level'] }}</dd></div>
                    <div><dt class="text-slate-500">Statut</dt><dd class="font-semibold">{{ str_replace('_', ' ', $property->status) }}</dd></div>
                </dl>
            </div>
        </section>

        <form method="POST" action="{{ route('projects.properties.destroy', [$project, $property]) }}" onsubmit="return confirm('Supprimer ce bien ?')" class="text-right">
            @csrf
            @method('DELETE')
            <button class="text-sm font-semibold text-red-700">Supprimer le bien</button>
        </form>
    </div>
</x-app-layout>
