<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-black uppercase text-teal-700">Dashboard</p>
            <h1 class="mt-1 text-3xl font-black text-slate-950">Redirection vers tes projets</h1>
        </div>
    </x-slot>

    <div class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="ir-panel p-6">
            <p class="text-slate-600">Le tableau de bord principal est la liste de tes projets immobiliers.</p>
            <a href="{{ route('projects.index') }}" class="ir-action-primary mt-4">Voir mes projets</a>
        </div>
    </div>
</x-app-layout>
