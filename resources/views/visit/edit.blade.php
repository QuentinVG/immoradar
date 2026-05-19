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
        $isDemoAccount = auth()->user()?->isDemoAccount() ?? false;
        $totalQuestions = $questions->flatten()->count();
        $answeredCount = $answers->filter(fn ($answer) => $answer->answer !== 'unknown')->count();
        $progress = $totalQuestions > 0 ? round(($answeredCount / $totalQuestions) * 100) : 0;
    @endphp

    <div
        class="mx-auto max-w-3xl px-4 py-6 sm:px-6 lg:px-8"
        x-data="window.visitChecklist({
            autosaveUrl: @js(route('projects.properties.visit.answer', [$project, $property])),
            csrfToken: @js(csrf_token()),
            demo: @js($isDemoAccount),
            initialAnswered: {{ $answeredCount }},
            total: {{ $totalQuestions }},
            initialCompatibility: {{ $scores['compatibility']['score'] }},
            initialVigilance: {{ $scores['vigilance']['score'] }}
        })"
    >
        @if(session('status'))
            <div class="mb-4 rounded-md border border-teal-200 bg-teal-50 p-4 text-sm font-semibold text-teal-900">{{ session('status') }}</div>
        @endif
        @if($isDemoAccount)
            <div class="mb-4 rounded-md border border-amber-200 bg-amber-50 p-4 text-sm font-semibold text-amber-950">
                Compte démo en lecture seule : les clics changent l'affichage, mais ne modifient pas les données. Crée ton compte pour sauvegarder.
            </div>
        @endif

        <div class="sticky top-16 z-10 -mx-4 border-b border-white/80 bg-white/90 px-4 py-3 shadow-sm shadow-slate-200/60 backdrop-blur sm:rounded-lg sm:border">
            <div class="flex items-center justify-between text-sm">
                <span class="font-black text-slate-950">Progression visite</span>
                <span class="font-semibold text-slate-600" x-text="answeredCount + '/' + total"></span>
            </div>
            <div class="mt-2 h-2 rounded-full bg-slate-100">
                <div class="h-2 rounded-full bg-teal-700" :style="progressStyle()"></div>
            </div>
            <div class="mt-2 flex flex-wrap items-center justify-between gap-2 text-xs">
                <p class="text-slate-500">Les réponses sont sauvegardées automatiquement sur ton compte.</p>
                <p class="font-black" :class="statusClass" x-text="status"></p>
            </div>
        </div>

        <form method="POST" action="{{ route('projects.properties.visit.update', [$project, $property]) }}" class="mt-6 space-y-6">
            @csrf
            @foreach($questions as $category => $categoryQuestions)
                <section class="ir-panel p-4">
                    <h2 class="text-lg font-black text-slate-950">{{ $category }}</h2>
                    <div class="mt-4 space-y-5">
                        @foreach($categoryQuestions as $question)
                            @php($current = $answers->get($question->id))
                            <div class="rounded-lg border border-slate-200 bg-white p-4" data-question="{{ $question->id }}">
                                <p class="font-black text-slate-950">{{ $question->question }}</p>
                                @if($question->help_text)
                                    <p class="mt-1 text-sm text-slate-500">{{ $question->help_text }}</p>
                                @endif
                                <div class="mt-3 grid grid-cols-2 gap-2">
                                    @foreach(['yes' => ['Oui', 'visit-choice-yes'], 'no' => ['Non', 'visit-choice-no'], 'unknown' => ['À vérifier', 'visit-choice-unknown'], 'not_applicable' => ['Non concerné', 'visit-choice-na']] as $value => [$label, $choiceClass])
                                        <label class="visit-choice {{ $choiceClass }}">
                                            <input class="sr-only" type="radio" name="answers[{{ $question->id }}][answer]" value="{{ $value }}" @checked(($current?->answer ?? 'unknown') === $value) @change="save($el, {{ $question->id }})">
                                            {{ $label }}
                                        </label>
                                    @endforeach
                                </div>
                                <textarea name="answers[{{ $question->id }}][comment]" rows="2" placeholder="Note rapide pendant la visite" class="mt-3 block w-full rounded-md border-slate-300 text-sm focus:border-teal-600 focus:ring-teal-600" @input.debounce.900ms="save($el, {{ $question->id }})">{{ old('answers.'.$question->id.'.comment', $current?->comment) }}</textarea>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endforeach

            <div class="ir-panel p-4">
                <h2 class="text-lg font-black text-slate-950">Résumé avant décision</h2>
                <div class="mt-3 grid grid-cols-2 gap-3 text-sm">
                    <div class="rounded-md bg-teal-50 p-3"><span class="text-slate-500">Compatibilité</span><strong class="block text-xl text-teal-800"><span x-text="compatibility"></span>/100</strong></div>
                    <div class="rounded-md bg-rose-50 p-3"><span class="text-slate-500">Vigilance</span><strong class="block text-xl text-rose-700"><span x-text="vigilance"></span>/100</strong></div>
                </div>
            </div>

            <div class="sticky bottom-0 -mx-4 border-t border-white/80 bg-white/90 px-4 py-3 backdrop-blur">
                <x-primary-button class="w-full justify-center">Enregistrer mes réponses</x-primary-button>
            </div>
        </form>
    </div>

    <script>
        window.visitChecklist = ({ autosaveUrl, csrfToken, demo, initialAnswered, total, initialCompatibility, initialVigilance }) => ({
            answeredCount: initialAnswered,
            total,
            compatibility: initialCompatibility,
            vigilance: initialVigilance,
            status: demo ? 'Démo lecture seule' : 'Sauvegarde automatique active',
            statusClass: demo ? 'text-amber-700' : 'text-teal-700',
            progressStyle() {
                const progress = this.total > 0 ? Math.round((this.answeredCount / this.total) * 100) : 0;
                return `width: ${progress}%`;
            },
            async save(element, questionId) {
                if (demo) {
                    this.status = 'Démo lecture seule';
                    this.statusClass = 'text-amber-700';
                    return;
                }

                const card = element.closest('[data-question]');
                const answer = card.querySelector('input[type="radio"]:checked')?.value ?? 'unknown';
                const comment = card.querySelector('textarea')?.value ?? '';

                this.status = 'Enregistrement...';
                this.statusClass = 'text-slate-500';

                try {
                    const response = await fetch(autosaveUrl, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: JSON.stringify({ question_id: questionId, answer, comment }),
                    });

                    const data = await response.json();
                    if (!response.ok) {
                        throw new Error(data.message || 'Réponse non enregistrée.');
                    }

                    this.answeredCount = data.answered_count;
                    this.total = data.total_questions;
                    this.compatibility = data.compatibility;
                    this.vigilance = data.vigilance;
                    this.status = 'Enregistré';
                    this.statusClass = 'text-teal-700';
                } catch (error) {
                    this.status = error.message || 'Erreur de sauvegarde';
                    this.statusClass = 'text-rose-700';
                }
            },
        });
    </script>
</x-app-layout>
