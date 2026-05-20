<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <p class="text-sm font-black uppercase text-teal-700">Biens</p>
                <h1 class="mt-1 text-3xl font-black text-slate-950">Biens à comparer</h1>
                <p class="mt-1 text-sm text-slate-600">{{ $project->name }}</p>
            </div>
            <a href="{{ route('projects.properties.create', $project) }}" class="ir-action-primary">Ajouter un bien</a>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @forelse($cards as $card)
                @php($property = $card['property'])
                <a href="{{ route('projects.properties.show', [$project, $property]) }}" class="ir-panel group overflow-hidden transition hover:-translate-y-0.5 hover:border-teal-300 hover:shadow-xl hover:shadow-teal-900/10">
                    <div class="flex h-36 items-center justify-center bg-gradient-to-br from-slate-100 via-teal-50 to-amber-50 text-sm font-semibold text-slate-500">
                        @if($property->main_photo_path)
                            <img src="{{ Storage::url($property->main_photo_path) }}" alt="{{ $property->title }}" class="h-full w-full object-cover">
                        @else
                            Photo à ajouter
                        @endif
                    </div>
                    <div class="p-5">
                        <h2 class="text-lg font-black text-slate-950 group-hover:text-teal-800">{{ $property->title }}</h2>
                        <p class="mt-1 text-sm text-slate-500">{{ $property->city }} · {{ $card['verdict'] }}</p>
                        <div class="mt-4 grid grid-cols-2 gap-3 text-sm">
                            <div class="rounded-md border border-teal-100 bg-teal-50 p-3 shadow-sm"><span class="text-slate-500">Compat.</span><strong class="block text-teal-800">{{ $card['compatibility'] }}/100</strong></div>
                            <div class="rounded-md border border-amber-100 bg-amber-50 p-3 shadow-sm"><span class="text-slate-500">Coût réel</span><strong class="block">{{ number_format($card['real_monthly_cost'], 0, ',', ' ') }} €</strong></div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="ir-panel border-dashed border-teal-300 p-8 text-center md:col-span-2 xl:col-span-3">
                    <h2 class="text-lg font-black text-slate-950">Aucun bien pour l’instant.</h2>
                    <p class="mt-2 text-slate-600">Ajoute les biens manuellement pour les comparer.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
