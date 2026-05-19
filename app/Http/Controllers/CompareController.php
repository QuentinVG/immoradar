<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Property;
use App\Services\ProjectSummaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class CompareController extends Controller
{
    public function __invoke(Request $request, Project $project, ProjectSummaryService $summaryService): View
    {
        $this->authorize('view', $project);

        $cards = $project->properties()
            ->with(['alerts', 'checklistAnswers.question'])
            ->get()
            ->map(fn (Property $property): array => $summaryService->propertyCard($property));
        $allCards = $cards;

        $sort = $request->string('sort', 'score')->toString();

        $cards = match ($sort) {
            'monthly_cost' => $cards->sortBy('real_monthly_cost'),
            'vigilance' => $cards->sortBy('vigilance'),
            'price' => $cards->sortBy(fn (array $card) => (float) ($card['property']->price ?? 0)),
            'projection' => $cards->sortByDesc('projection'),
            default => $cards->sortByDesc('compatibility'),
        };

        return view('projects.compare', [
            'project' => $project,
            'cards' => $cards->values(),
            'highlights' => $this->highlights($allCards),
            'sort' => $sort,
        ]);
    }

    /**
     * @param  Collection<int,array<string,mixed>>  $cards
     * @return array<string,array<string,mixed>|null>
     */
    private function highlights(Collection $cards): array
    {
        return [
            'best_score' => $cards->sortByDesc('compatibility')->first(),
            'lowest_cost' => $cards->filter(fn (array $card): bool => $card['real_monthly_cost'] > 0)->sortBy('real_monthly_cost')->first(),
            'lowest_risk' => $cards->sortBy('vigilance')->first(),
            'best_projection' => $cards->sortByDesc('projection')->first(),
        ];
    }
}
