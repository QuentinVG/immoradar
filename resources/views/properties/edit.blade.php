<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-black uppercase text-teal-700">Modifier</p>
            <h1 class="mt-1 text-3xl font-black text-slate-950">Mettre à jour le bien</h1>
            <p class="mt-1 text-sm text-slate-600">Complète seulement les infos qui changent vraiment la décision.</p>
        </div>
    </x-slot>

    <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
        <form method="POST" action="{{ route('projects.properties.update', [$project, $property]) }}" enctype="multipart/form-data">
            @method('PUT')
            @include('properties._form', ['submitLabel' => 'Enregistrer'])
        </form>
    </div>
</x-app-layout>
