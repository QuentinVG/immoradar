<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Property;
use App\Services\ProjectSummaryService;
use App\Services\PropertyAlertService;
use App\Services\PropertyCostCalculator;
use App\Services\PropertyScoringService;
use App\Services\PropertyVerdictService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class ReportController extends Controller
{
    public function property(
        Project $project,
        Property $property,
        PropertyCostCalculator $costCalculator,
        PropertyScoringService $scoringService,
        PropertyVerdictService $verdictService,
        PropertyAlertService $alertService,
    ): Response {
        $this->guardProperty($project, $property);
        $alertService->refresh($property);
        $property->load(['alerts', 'checklistAnswers.question']);

        return Pdf::loadView('reports.property', [
            'project' => $project,
            'property' => $property,
            'cost' => $costCalculator->calculate($property),
            'scores' => $scoringService->score($property),
            'verdict' => $verdictService->verdict($property),
        ])->download('rapport-'.$property->id.'.pdf');
    }

    public function project(Project $project, ProjectSummaryService $summaryService): Response
    {
        $this->authorize('view', $project);

        return Pdf::loadView('reports.project', [
            'project' => $project,
            'summary' => $summaryService->summarize($project),
        ])->download('rapport-projet-'.$project->id.'.pdf');
    }

    private function guardProperty(Project $project, Property $property): void
    {
        $this->authorize('view', $project);
        abort_unless($property->project_id === $project->id, 404);
        $this->authorize('view', $property);
    }
}
