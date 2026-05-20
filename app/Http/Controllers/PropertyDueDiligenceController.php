<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateDueDiligenceRequest;
use App\Models\Project;
use App\Models\Property;
use App\Services\PropertyAlertService;
use App\Services\PropertyDueDiligenceService;
use Illuminate\Http\RedirectResponse;

class PropertyDueDiligenceController extends Controller
{
    public function update(
        UpdateDueDiligenceRequest $request,
        Project $project,
        Property $property,
        PropertyDueDiligenceService $dueDiligenceService,
        PropertyAlertService $alertService,
    ): RedirectResponse {
        $this->authorize('view', $project);
        abort_unless($property->project_id === $project->id, 404);
        $this->authorize('update', $property);

        $items = collect($request->validated('items'))
            ->map(fn (array $item): array => [
                'key' => $item['key'],
                'status' => $item['status'],
                'is_blocking' => (bool) ($item['is_blocking'] ?? false),
                'note' => $item['note'] ?? null,
            ])
            ->values()
            ->all();

        $dueDiligenceService->update($property, $items);
        $alertService->refresh($property);

        return redirect()
            ->route('projects.properties.show', [$project, $property])
            ->with('status', 'Revue avant offre mise à jour.');
    }
}
