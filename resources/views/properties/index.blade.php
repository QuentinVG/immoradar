<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Biens</h1>
                <p class="mt-1 text-sm text-slate-600">{{ $project->name }}</p>
            </div>
            <a href="{{ route('projects.properties.create', $project) }}" class="rounded-md bg-emerald-700 px-4 py-2 text-sm font-semibold text-white">Ajouter un bien</a>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @forelse($cards as $card)
                @php($property = $card['property'])
                <a href="{{ route('projects.properties.show', [$project, $property]) }}" class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                    <div class="flex h-36 items-center justify-center bg-slate-100 text-sm text-slate-500">
                        @if($property->main_photo_path)
                            <img src="{{ Storage::url($property->main_photo_path) }}" alt="{{ $property->title }}" class="h-full w-full object-cover">
                        @else
                            Photo à ajouter
                        @endif
                    </div>
                    <div class="p-5">
                        <h2 class="text-lg font-semibold text-slate-900">{{ $property->title }}</h2>
                        <p class="mt-1 text-sm text-slate-500">{{ $property->city }} · {{ $card['verdict'] }}</p>
                        <div class="mt-4 grid grid-cols-2 gap-3 text-sm">
                            <div><span class="text-slate-500">Compat.</span><strong class="block">{{ $card['compatibility'] }}/100</strong></div>
                            <div><span class="text-slate-500">Coût réel</span><strong class="block">{{ number_format($card['real_monthly_cost'], 0, ',', ' ') }} €</strong></div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="rounded-lg border border-dashed border-slate-300 bg-white p-8 text-center md:col-span-2 xl:col-span-3">
                    <h2 class="text-lg font-semibold text-slate-900">Aucun bien pour l’instant.</h2>
                    <p class="mt-2 text-slate-600">Ajoute les biens manuellement pour les comparer.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
