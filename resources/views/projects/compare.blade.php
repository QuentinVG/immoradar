<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <p class="text-sm font-black uppercase text-teal-700">Comparateur</p>
                <h1 class="mt-1 text-3xl font-black text-slate-950">Comparer les biens</h1>
                <p class="mt-1 text-sm text-slate-600">{{ $project->name }}</p>
            </div>
            <a href="{{ route('projects.show', $project) }}" class="ir-action-secondary">Retour projet</a>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <form method="GET" class="ir-soft-panel mb-4 inline-flex items-center gap-3 p-3">
            <label class="text-sm font-black text-slate-700">Trier par</label>
            <select name="sort" onchange="this.form.submit()" class="rounded-md border-slate-300 text-sm focus:border-teal-600 focus:ring-teal-600">
                @foreach(['score' => 'Meilleur score', 'monthly_cost' => 'Coût mensuel', 'vigilance' => 'Vigilance', 'price' => 'Prix', 'projection' => 'Projection'] as $value => $label)
                    <option value="{{ $value }}" @selected($sort === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </form>

        <div class="ir-panel overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-left text-xs font-black uppercase text-slate-500">
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
                                <a href="{{ route('projects.properties.show', [$project, $property]) }}" class="font-black text-slate-950 hover:text-teal-800">{{ $property->title }}</a>
                                <p class="text-xs text-slate-500">{{ $property->city }} · {{ $property->status }}</p>
                            </td>
                            <td class="px-4 py-3">{{ $property->price ? number_format((float) $property->price, 0, ',', ' ') . ' €' : '—' }}</td>
                            <td class="px-4 py-3">{{ $property->surface ? number_format((float) $property->surface, 0, ',', ' ') . ' m²' : '—' }}</td>
                            <td class="px-4 py-3">{{ $property->price_per_square_meter ? number_format($property->price_per_square_meter, 0, ',', ' ') . ' €' : '—' }}</td>
                            <td class="px-4 py-3">{{ number_format($card['real_monthly_cost'], 0, ',', ' ') }} €/mois</td>
                            <td class="px-4 py-3">{{ $property->dpe }}</td>
                            <td class="px-4 py-3">{{ $property->commute_minutes ? $property->commute_minutes . ' min' : '—' }}</td>
                            <td class="px-4 py-3 font-black text-teal-800">{{ $card['compatibility'] }}</td>
                            <td class="px-4 py-3">{{ $card['solidity'] }}</td>
                            <td class="px-4 py-3">{{ $card['projection'] }}</td>
                            <td class="px-4 py-3 font-black text-rose-700">{{ $card['vigilance'] }}</td>
                            <td class="px-4 py-3"><span class="rounded-md bg-amber-50 px-2 py-1 text-xs font-black text-amber-900">{{ $card['verdict'] }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="12" class="px-4 py-8 text-center text-slate-600">Aucun bien à comparer.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
