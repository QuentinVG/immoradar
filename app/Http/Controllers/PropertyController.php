<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePropertyRequest;
use App\Http\Requests\UpdatePropertyRequest;
use App\Models\Project;
use App\Models\Property;
use App\Models\VisitChecklistQuestion;
use App\Services\ProjectSummaryService;
use App\Services\PropertyAlertService;
use App\Services\PropertyCostCalculator;
use App\Services\PropertyDueDiligenceService;
use App\Services\PropertyOfferReadinessService;
use App\Services\PropertyScoringService;
use App\Services\PropertyVerdictService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PropertyController extends Controller
{
    public function index(Project $project, ProjectSummaryService $summaryService): View
    {
        $this->authorize('view', $project);

        $cards = $project->properties()
            ->with(['alerts', 'checklistAnswers.question'])
            ->latest()
            ->get()
            ->map(fn (Property $property): array => $summaryService->propertyCard($property));

        return view('properties.index', compact('project', 'cards'));
    }

    public function create(Project $project): View
    {
        $this->authorize('view', $project);

        return view('properties.create', ['project' => $project, 'property' => new Property(['transaction_type' => 'achat', 'dpe' => 'inconnu', 'status' => 'nouveau'])]);
    }

    public function store(StorePropertyRequest $request, Project $project, PropertyAlertService $alertService): RedirectResponse
    {
        $this->authorize('view', $project);

        /** @var Property $property */
        $property = $project->properties()->create($this->propertyData($request));
        $this->storePhoto($request, $property);
        $alertService->refresh($property);

        return redirect()->route('projects.properties.show', [$project, $property])->with('status', 'Bien ajouté.');
    }

    public function show(
        Project $project,
        Property $property,
        PropertyCostCalculator $costCalculator,
        PropertyScoringService $scoringService,
        PropertyVerdictService $verdictService,
        PropertyAlertService $alertService,
        PropertyDueDiligenceService $dueDiligenceService,
        PropertyOfferReadinessService $offerReadinessService,
    ): View {
        $this->guardProperty($project, $property);
        $alertService->refresh($property);
        $property->load(['alerts', 'checklistAnswers.question']);
        $activeQuestions = VisitChecklistQuestion::query()->where('is_active', true)->count();
        $answeredQuestions = $property->checklistAnswers->where('answer', '!=', 'unknown')->count();

        return view('properties.show', [
            'project' => $project,
            'property' => $property,
            'cost' => $costCalculator->calculate($property),
            'scores' => $scoringService->score($property),
            'verdict' => $verdictService->verdict($property),
            'checklistProgress' => $activeQuestions > 0 ? (int) round(($answeredQuestions / $activeQuestions) * 100) : 0,
            'answeredQuestions' => $answeredQuestions,
            'activeQuestions' => $activeQuestions,
            'dueDiligence' => $dueDiligenceService->review($property),
            'offerReadiness' => $offerReadinessService->review($property),
        ]);
    }

    public function edit(Project $project, Property $property): View
    {
        $this->guardProperty($project, $property);

        return view('properties.edit', compact('project', 'property'));
    }

    public function update(UpdatePropertyRequest $request, Project $project, Property $property, PropertyAlertService $alertService): RedirectResponse
    {
        $this->guardProperty($project, $property);
        $property->update($this->propertyData($request));
        $this->storePhoto($request, $property);
        $alertService->refresh($property);

        return redirect()->route('projects.properties.show', [$project, $property])->with('status', 'Bien mis à jour.');
    }

    public function destroy(Project $project, Property $property): RedirectResponse
    {
        $this->guardProperty($project, $property);

        if ($property->main_photo_path) {
            Storage::disk('public')->delete($property->main_photo_path);
        }

        $property->delete();

        return redirect()->route('projects.properties.index', $project)->with('status', 'Bien supprimé.');
    }

    private function guardProperty(Project $project, Property $property): void
    {
        $this->authorize('view', $project);
        abort_unless($property->project_id === $project->id, 404);
        $this->authorize('view', $property);
    }

    /**
     * @return array<string,mixed>
     */
    private function propertyData(StorePropertyRequest|UpdatePropertyRequest $request): array
    {
        $data = $request->validated();
        unset($data['main_photo']);

        foreach (['has_garage', 'has_parking', 'has_balcony', 'has_garden', 'has_cellar', 'has_elevator'] as $field) {
            $data[$field] = $request->boolean($field);
        }

        return $data;
    }

    private function storePhoto(StorePropertyRequest|UpdatePropertyRequest $request, Property $property): void
    {
        if (! $request->hasFile('main_photo')) {
            return;
        }

        if ($property->main_photo_path) {
            Storage::disk('public')->delete($property->main_photo_path);
        }

        $property->update([
            'main_photo_path' => $request->file('main_photo')->store('properties', 'public'),
        ]);
    }
}
