<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-slate-900">Ajouter un bien</h1>
    </x-slot>

    <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
        <form method="POST" action="{{ route('projects.properties.store', $project) }}" enctype="multipart/form-data">
            @include('properties._form', ['submitLabel' => 'Ajouter le bien'])
        </form>
    </div>
</x-app-layout>
