<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-black uppercase text-teal-700">Ajouter un bien</p>
            <h1 class="mt-1 text-3xl font-black text-slate-950">Commence simple, complète après.</h1>
            <p class="mt-1 text-sm text-slate-600">Les champs essentiels suffisent pour créer le bien. Le budget détaillé peut attendre.</p>
        </div>
    </x-slot>

    <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
        <form method="POST" action="{{ route('projects.properties.store', $project) }}" enctype="multipart/form-data">
            @include('properties._form', ['submitLabel' => 'Ajouter le bien'])
        </form>
    </div>
</x-app-layout>
