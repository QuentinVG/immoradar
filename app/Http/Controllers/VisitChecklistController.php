<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreChecklistAnswerRequest;
use App\Models\Project;
use App\Models\Property;
use App\Models\VisitChecklistQuestion;
use App\Services\PropertyAlertService;
use App\Services\PropertyScoringService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class VisitChecklistController extends Controller
{
    public function edit(Request $request, Project $project, Property $property, PropertyScoringService $scoringService): View
    {
        $this->guardProperty($project, $property);

        $visitMode = $this->visitMode($request);
        $visibleQuestions = $this->visibleQuestions($visitMode);
        $questions = $visibleQuestions
            ->groupBy('category')
            ->sortBy(function ($items, string $category): int {
                $position = array_search($category, $this->categoryOrder(), true);

                return $position === false ? 99 : $position;
            });

        $answers = $property->checklistAnswers()->get()->keyBy('visit_checklist_question_id');
        $visitSummary = $this->visitSummary($property, $visibleQuestions);

        return view('visit.edit', [
            'project' => $project,
            'property' => $property,
            'questions' => $questions,
            'visibleQuestions' => $visibleQuestions,
            'answers' => $answers,
            'scores' => $scoringService->score($property),
            'visitSummary' => $visitSummary,
            'visitMode' => $visitMode,
            'visitModes' => $this->visitModes(),
        ]);
    }

    public function update(StoreChecklistAnswerRequest $request, Project $project, Property $property, PropertyAlertService $alertService): RedirectResponse
    {
        $this->guardProperty($project, $property);

        foreach ($request->validated('answers') as $questionId => $payload) {
            $question = VisitChecklistQuestion::findOrFail($questionId);
            $answer = $payload['answer'];

            $this->saveAnswer($property, $question, $answer, $payload['comment'] ?? null);
        }

        $alertService->refresh($property);

        return redirect()->route('projects.properties.visit', [$project, $property, 'mode' => $this->visitMode($request)])
            ->with('status', 'Checklist enregistrée.');
    }

    public function updateAnswer(
        Request $request,
        Project $project,
        Property $property,
        PropertyAlertService $alertService,
        PropertyScoringService $scoringService,
    ): JsonResponse {
        $this->guardProperty($project, $property);

        $data = $request->validate([
            'question_id' => ['required', 'integer', 'exists:visit_checklist_questions,id'],
            'answer' => ['required', 'in:yes,no,unknown,not_applicable'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        $question = VisitChecklistQuestion::query()
            ->where('is_active', true)
            ->findOrFail($data['question_id']);

        $this->saveAnswer($property, $question, $data['answer'], $data['comment'] ?? null);
        $alertService->refresh($property);

        $property->load('checklistAnswers.question');
        $scores = $scoringService->score($property);
        $visibleQuestions = $this->visibleQuestions($this->visitMode($request));
        $visitSummary = $this->visitSummary($property, $visibleQuestions);

        return response()->json([
            'message' => 'Réponse enregistrée.',
            'answered_count' => $visitSummary['answered_count'],
            'total_questions' => $visitSummary['total_questions'],
            'progress' => $visitSummary['progress'],
            'compatibility' => $scores['compatibility']['score'],
            'vigilance' => $scores['vigilance']['score'],
            'risk_count' => $visitSummary['risk_count'],
            'unknown_count' => $visitSummary['unknown_count'],
            'critical_missing_count' => $visitSummary['critical_missing_count'],
        ]);
    }

    private function guardProperty(Project $project, Property $property): void
    {
        $this->authorize('view', $project);
        abort_unless($property->project_id === $project->id, 404);
        $this->authorize('view', $property);
    }

    private function answerScore(string $answer, int $weight): int
    {
        return match ($answer) {
            'yes' => $weight,
            'no' => -$weight,
            default => 0,
        };
    }

    private function saveAnswer(Property $property, VisitChecklistQuestion $question, string $answer, ?string $comment): void
    {
        $property->checklistAnswers()->updateOrCreate(
            ['visit_checklist_question_id' => $question->id],
            [
                'answer' => $answer,
                'score' => $this->answerScore($answer, $question->weight),
                'comment' => $comment,
            ],
        );
    }

    /**
     * @return Collection<int,VisitChecklistQuestion>
     */
    private function activeQuestions(): Collection
    {
        return VisitChecklistQuestion::query()
            ->where('is_active', true)
            ->orderBy('id')
            ->get()
            ->sortBy(function (VisitChecklistQuestion $question): int {
                $position = array_search($question->category, $this->categoryOrder(), true);

                return $position === false ? 99 : $position;
            })
            ->values();
    }

    /**
     * @return Collection<int,VisitChecklistQuestion>
     */
    private function visibleQuestions(string $mode): Collection
    {
        $questions = $this->activeQuestions();

        return match ($mode) {
            'full' => $questions,
            'standard' => $questions->take(18)->values(),
            default => $this->expressQuestions($questions),
        };
    }

    /**
     * @param  Collection<int,VisitChecklistQuestion>  $questions
     * @return Collection<int,VisitChecklistQuestion>
     */
    private function expressQuestions(Collection $questions): Collection
    {
        $priority = [
            'Le trajet vers le travail est-il acceptable ?',
            'La luminosité est-elle suffisante ?',
            'L’isolation sonore semble-t-elle correcte ?',
            'Y a-t-il des traces d’humidité ?',
            'Y a-t-il des fissures inquiétantes ?',
            'Les travaux semblent-ils maîtrisables ?',
            'Le dossier de diagnostics est-il disponible ?',
            'Est-ce que je me projette dans ce logement ?',
        ];

        $selected = $questions
            ->filter(fn (VisitChecklistQuestion $question): bool => in_array($question->question, $priority, true))
            ->values();

        if ($selected->count() >= 8) {
            return $selected->take(8)->values();
        }

        $selectedIds = $selected->pluck('id')->all();

        return $selected
            ->concat($questions->reject(fn (VisitChecklistQuestion $question): bool => in_array($question->id, $selectedIds, true))->take(8 - $selected->count()))
            ->values();
    }

    /**
     * @param  Collection<int,VisitChecklistQuestion>  $questions
     * @return array{answered_count:int,total_questions:int,progress:int,risk_count:int,unknown_count:int,critical_missing_count:int}
     */
    private function visitSummary(Property $property, Collection $questions): array
    {
        $property->loadMissing('checklistAnswers.question');
        $answers = $property->checklistAnswers->keyBy('visit_checklist_question_id');
        $answeredCount = $questions
            ->filter(fn (VisitChecklistQuestion $question): bool => ($answers->get($question->id)->answer ?? 'unknown') !== 'unknown')
            ->count();
        $totalQuestions = $questions->count();

        return [
            'answered_count' => $answeredCount,
            'total_questions' => $totalQuestions,
            'progress' => $totalQuestions > 0 ? (int) round(($answeredCount / $totalQuestions) * 100) : 0,
            'risk_count' => $questions
                ->filter(fn (VisitChecklistQuestion $question): bool => ($answers->get($question->id)->answer ?? null) === 'no' && $question->category !== 'Ressenti')
                ->count(),
            'unknown_count' => $questions
                ->filter(fn (VisitChecklistQuestion $question): bool => ($answers->get($question->id)->answer ?? null) === 'unknown')
                ->count(),
            'critical_missing_count' => $questions
                ->filter(fn (VisitChecklistQuestion $question): bool => $question->weight >= 2 && ! in_array($answers->get($question->id)->answer ?? null, ['yes', 'not_applicable'], true))
                ->count(),
        ];
    }

    /**
     * @return array<int,string>
     */
    private function categoryOrder(): array
    {
        return ['Quartier', 'Immeuble / extérieur', 'Intérieur', 'Technique', 'Budget', 'Documents', 'Ressenti'];
    }

    /**
     * @return array<string,array{label:string,description:string}>
     */
    private function visitModes(): array
    {
        return [
            'express' => ['label' => 'Visite express', 'description' => '8 questions critiques'],
            'standard' => ['label' => 'Visite standard', 'description' => 'les points importants'],
            'full' => ['label' => 'Visite complète', 'description' => 'checklist complète'],
        ];
    }

    private function visitMode(Request $request): string
    {
        $mode = (string) $request->query('mode', $request->input('mode', 'express'));

        return array_key_exists($mode, $this->visitModes()) ? $mode : 'express';
    }
}
