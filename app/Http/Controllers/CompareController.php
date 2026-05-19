<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Property;
use App\Services\ProjectSummaryService;
use Illuminate\Http\Request;
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
            'sort' => $sort,
        ]);
    }
}
