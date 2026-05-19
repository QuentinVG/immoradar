<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Comparer les biens</h1>
                <p class="mt-1 text-sm text-slate-600">{{ $project->name }}</p>
            </div>
            <a href="{{ route('projects.show', $project) }}" class="rounded-md border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700">Retour projet</a>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <form method="GET" class="mb-4">
            <label class="text-sm font-medium text-slate-700">Trier par</label>
            <select name="sort" onchange="this.form.submit()" class="ml-2 rounded-md border-slate-300 text-sm">
                @foreach(['score' => 'Meilleur score', 'monthly_cost' => 'Coût mensuel', 'vigilance' => 'Vigilance', 'price' => 'Prix', 'projection' => 'Projection'] as $value => $label)
                    <option value="{{ $value }}" @selected($sort === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </form>

        <div class="overflow-x-auto rounded-lg border border-slate-200 bg-white">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-left text-xs font-semibold uppercase text-slate-500">
                    <tr>
                        <th class="px-4 py-3">Bien</th>
                        <th class="px-4 py-3">Prix</th>
                        <th class="px-4 py-3">Surface</th>
                        <th class="px-4 py-3">€/m²</th>
                        <th class="px-4 py-3">Coût réel</th>
                        <th class="px-4 py-3">DPE</th>
                        <th class="px-4 py-3">Trajet</th>
                        <th class="px-4 py-3">Compat.</th>
                        <th class="px-4 py-3">Solidité</th>
                        <th class="px-4 py-3">Projection</th>
                        <th class="px-4 py-3">Vigilance</th>
                        <th class="px-4 py-3">Verdict</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($cards as $card)
                        @php($property = $card['property'])
                        <tr>
                            <td class="px-4 py-3">
                                <a href="{{ route('projects.properties.show', [$project, $property]) }}" class="font-semibold text-slate-900">{{ $property->title }}</a>
                                <p class="text-xs text-slate-500">{{ $property->city }} · {{ $property->status }}</p>
                            </td>
                            <td class="px-4 py-3">{{ $property->price ? number_format((float) $property->price, 0, ',', ' ') . ' €' : '—' }}</td>
                            <td class="px-4 py-3">{{ $property->surface ? number_format((float) $property->surface, 0, ',', ' ') . ' m²' : '—' }}</td>
                            <td class="px-4 py-3">{{ $property->price_per_square_meter ? number_format($property->price_per_square_meter, 0, ',', ' ') . ' €' : '—' }}</td>
                            <td class="px-4 py-3">{{ number_format($card['real_monthly_cost'], 0, ',', ' ') }} €/mois</td>
                            <td class="px-4 py-3">{{ $property->dpe }}</td>
                            <td class="px-4 py-3">{{ $property->commute_minutes ? $property->commute_minutes . ' min' : '—' }}</td>
                            <td class="px-4 py-3 font-semibold">{{ $card['compatibility'] }}</td>
                            <td class="px-4 py-3">{{ $card['solidity'] }}</td>
                            <td class="px-4 py-3">{{ $card['projection'] }}</td>
                            <td class="px-4 py-3">{{ $card['vigilance'] }}</td>
                            <td class="px-4 py-3">{{ $card['verdict'] }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="12" class="px-4 py-8 text-center text-slate-600">Aucun bien à comparer.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
