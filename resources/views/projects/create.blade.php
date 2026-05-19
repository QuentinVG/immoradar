<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-slate-900">Créer un projet</h1>
    </x-slot>

    <div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
        <form method="POST" action="{{ route('projects.store') }}">
            @include('projects._form', ['submitLabel' => 'Créer le projet'])
        </form>
    </div>
</x-app-layout>
