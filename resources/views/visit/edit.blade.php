<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <p class="text-sm font-black uppercase text-teal-700">Mode visite</p>
                <h1 class="mt-1 text-3xl font-black text-slate-950">{{ $property->title }}</h1>
                <p class="mt-1 text-sm text-slate-600">Réponds vite pendant la visite. Tu peux revenir modifier après.</p>
            </div>
            <a href="{{ route('projects.properties.show', [$project, $property]) }}" class="ir-action-secondary">Retour fiche</a>
        </div>
    </x-slot>

    @php
        $totalQuestions = $questions->flatten()->count();
        $answeredCount = $answers->filter(fn ($answer) => $answer->answer !== 'unknown')->count();
        $progress = $totalQuestions > 0 ? round(($answeredCount / $totalQuestions) * 100) : 0;
    @endphp

    <div class="mx-auto max-w-3xl px-4 py-6 sm:px-6 lg:px-8">
        @if(session('status'))
            <div class="mb-4 rounded-md border border-teal-200 bg-teal-50 p-4 text-sm font-semibold text-teal-900">{{ session('status') }}</div>
        @endif

        <div class="sticky top-16 z-10 -mx-4 border-b border-white/80 bg-white/90 px-4 py-3 shadow-sm shadow-slate-200/60 backdrop-blur sm:rounded-lg sm:border">
            <div class="flex items-center justify-between text-sm">
                <span class="font-black text-slate-950">Progression visite</span>
                <span class="font-semibold text-slate-600">{{ $answeredCount }}/{{ $totalQuestions }}</span>
            </div>
            <div class="mt-2 h-2 rounded-full bg-slate-100">
                <div class="h-2 rounded-full bg-teal-700" style="width: {{ $progress }}%"></div>
            </div>
            <p class="mt-2 text-xs text-slate-500">Compte démo ou compte perso : les réponses sont enregistrées après le bouton en bas.</p>
        </div>

        <form method="POST" action="{{ route('projects.properties.visit.update', [$project, $property]) }}" class="mt-6 space-y-6">
            @csrf
            @foreach($questions as $category => $categoryQuestions)
                <section class="ir-panel p-4">
                    <h2 class="text-lg font-black text-slate-950">{{ $category }}</h2>
                    <div class="mt-4 space-y-5">
                        @foreach($categoryQuestions as $question)
                            @php($current = $answers->get($question->id))
                            <div class="rounded-lg border border-slate-200 bg-white p-4">
                                <p class="font-black text-slate-950">{{ $question->question }}</p>
                                @if($question->help_text)
                                    <p class="mt-1 text-sm text-slate-500">{{ $question->help_text }}</p>
                                @endif
                                <div class="mt-3 grid grid-cols-2 gap-2">
                                    @foreach(['yes' => 'Oui', 'no' => 'Non', 'unknown' => 'À vérifier', 'not_applicable' => 'Non concerné'] as $value => $label)
                                        <label class="flex min-h-12 cursor-pointer items-center justify-center rounded-md border px-3 py-2 text-center text-sm font-black transition {{ ($current?->answer ?? 'unknown') === $value ? ($value === 'no' ? 'border-rose-500 bg-rose-50 text-rose-900' : ($value === 'yes' ? 'border-teal-600 bg-teal-50 text-teal-900' : 'border-amber-500 bg-amber-50 text-amber-950')) : 'border-slate-200 bg-white text-slate-700 hover:border-teal-300' }}">
                                            <input class="sr-only" type="radio" name="answers[{{ $question->id }}][answer]" value="{{ $value }}" @checked(($current?->answer ?? 'unknown') === $value)>
                                            {{ $label }}
                                        </label>
                                    @endforeach
                                </div>
                                <textarea name="answers[{{ $question->id }}][comment]" rows="2" placeholder="Note rapide pendant la visite" class="mt-3 block w-full rounded-md border-slate-300 text-sm focus:border-teal-600 focus:ring-teal-600">{{ old('answers.'.$question->id.'.comment', $current?->comment) }}</textarea>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endforeach

            <div class="ir-panel p-4">
                <h2 class="text-lg font-black text-slate-950">Résumé avant décision</h2>
                <div class="mt-3 grid grid-cols-2 gap-3 text-sm">
                    <div class="rounded-md bg-teal-50 p-3"><span class="text-slate-500">Compatibilité</span><strong class="block text-xl text-teal-800">{{ $scores['compatibility']['score'] }}/100</strong></div>
                    <div class="rounded-md bg-rose-50 p-3"><span class="text-slate-500">Vigilance</span><strong class="block text-xl text-rose-700">{{ $scores['vigilance']['score'] }}/100</strong></div>
                </div>
            </div>

            <div class="sticky bottom-0 -mx-4 border-t border-white/80 bg-white/90 px-4 py-3 backdrop-blur">
                <x-primary-button class="w-full justify-center">Enregistrer mes réponses</x-primary-button>
            </div>
        </form>
    </div>
</x-app-layout>
