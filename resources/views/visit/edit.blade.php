<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <p class="text-sm font-semibold uppercase text-emerald-700">Mode visite</p>
                <h1 class="text-2xl font-bold text-slate-900">{{ $property->title }}</h1>
            </div>
            <a href="{{ route('projects.properties.show', [$project, $property]) }}" class="rounded-md border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700">Retour fiche</a>
        </div>
    </x-slot>

    @php
        $totalQuestions = $questions->flatten()->count();
        $answeredCount = $answers->filter(fn ($answer) => $answer->answer !== 'unknown')->count();
        $progress = $totalQuestions > 0 ? round(($answeredCount / $totalQuestions) * 100) : 0;
    @endphp

    <div class="mx-auto max-w-3xl px-4 py-6 sm:px-6 lg:px-8">
        @if(session('status'))
            <div class="mb-4 rounded-md bg-emerald-50 p-4 text-sm text-emerald-800">{{ session('status') }}</div>
        @endif

        <div class="sticky top-0 z-10 -mx-4 border-b border-slate-200 bg-slate-100/95 px-4 py-3 backdrop-blur sm:rounded-lg sm:border">
            <div class="flex items-center justify-between text-sm">
                <span class="font-semibold text-slate-900">Progression</span>
                <span class="text-slate-600">{{ $answeredCount }}/{{ $totalQuestions }}</span>
            </div>
            <div class="mt-2 h-2 rounded-full bg-white">
                <div class="h-2 rounded-full bg-emerald-700" style="width: {{ $progress }}%"></div>
            </div>
        </div>

        <form method="POST" action="{{ route('projects.properties.visit.update', [$project, $property]) }}" class="mt-6 space-y-6">
            @csrf
            @foreach($questions as $category => $categoryQuestions)
                <section class="rounded-lg bg-white p-4 shadow-sm">
                    <h2 class="text-lg font-bold text-slate-900">{{ $category }}</h2>
                    <div class="mt-4 space-y-5">
                        @foreach($categoryQuestions as $question)
                            @php($current = $answers->get($question->id))
                            <div class="rounded-md border border-slate-200 p-4">
                                <p class="font-semibold text-slate-900">{{ $question->question }}</p>
                                @if($question->help_text)
                                    <p class="mt-1 text-sm text-slate-500">{{ $question->help_text }}</p>
                                @endif
                                <div class="mt-3 grid grid-cols-2 gap-2">
                                    @foreach(['yes' => 'Oui', 'no' => 'Non', 'unknown' => 'À vérifier', 'not_applicable' => 'Non concerné'] as $value => $label)
                                        <label class="flex items-center justify-center rounded-md border px-3 py-2 text-sm font-semibold {{ ($current?->answer ?? 'unknown') === $value ? 'border-emerald-700 bg-emerald-50 text-emerald-900' : 'border-slate-200 text-slate-700' }}">
                                            <input class="sr-only" type="radio" name="answers[{{ $question->id }}][answer]" value="{{ $value }}" @checked(($current?->answer ?? 'unknown') === $value)>
                                            {{ $label }}
                                        </label>
                                    @endforeach
                                </div>
                                <textarea name="answers[{{ $question->id }}][comment]" rows="2" placeholder="Note rapide pendant la visite" class="mt-3 block w-full rounded-md border-slate-300 text-sm">{{ old("answers.$question->id.comment", $current?->comment) }}</textarea>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endforeach

            <div class="rounded-lg bg-white p-4 shadow-sm">
                <h2 class="text-lg font-bold text-slate-900">Résumé avant décision</h2>
                <div class="mt-3 grid grid-cols-2 gap-3 text-sm">
                    <div class="rounded-md bg-slate-50 p-3"><span class="text-slate-500">Compatibilité</span><strong class="block">{{ $scores['compatibility']['score'] }}/100</strong></div>
                    <div class="rounded-md bg-slate-50 p-3"><span class="text-slate-500">Vigilance</span><strong class="block">{{ $scores['vigilance']['score'] }}/100</strong></div>
                </div>
            </div>

            <div class="sticky bottom-0 -mx-4 bg-slate-100 px-4 py-3">
                <x-primary-button class="w-full justify-center">Enregistrer la visite</x-primary-button>
            </div>
        </form>
    </div>
</x-app-layout>
