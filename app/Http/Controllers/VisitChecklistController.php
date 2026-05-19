<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreChecklistAnswerRequest;
use App\Models\Project;
use App\Models\Property;
use App\Models\VisitChecklistQuestion;
use App\Services\PropertyAlertService;
use App\Services\PropertyScoringService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class VisitChecklistController extends Controller
{
    public function edit(Project $project, Property $property, PropertyScoringService $scoringService): View
    {
        $this->guardProperty($project, $property);

        $questions = VisitChecklistQuestion::query()
            ->where('is_active', true)
            ->orderBy('category')
            ->orderBy('id')
            ->get()
            ->groupBy('category');

        $answers = $property->checklistAnswers()->get()->keyBy('visit_checklist_question_id');

        return view('visit.edit', [
            'project' => $project,
            'property' => $property,
            'questions' => $questions,
            'answers' => $answers,
            'scores' => $scoringService->score($property),
        ]);
    }

    public function update(StoreChecklistAnswerRequest $request, Project $project, Property $property, PropertyAlertService $alertService): RedirectResponse
    {
        $this->guardProperty($project, $property);

        foreach ($request->validated('answers') as $questionId => $payload) {
            $question = VisitChecklistQuestion::findOrFail($questionId);
            $answer = $payload['answer'];

            $property->checklistAnswers()->updateOrCreate(
                ['visit_checklist_question_id' => $question->id],
                [
                    'answer' => $answer,
                    'score' => $this->answerScore($answer, $question->weight),
                    'comment' => $payload['comment'] ?? null,
                ],
            );
        }

        $alertService->refresh($property);

        return redirect()->route('projects.properties.visit', [$project, $property])->with('status', 'Checklist enregistrée.');
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
}
