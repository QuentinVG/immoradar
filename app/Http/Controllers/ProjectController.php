<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use App\Services\ProjectSummaryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(): View
    {
        $projects = Auth::user()
            ->projects()
            ->withCount('properties')
            ->latest()
            ->get();

        return view('projects.index', compact('projects'));
    }

    public function create(): View
    {
        $this->authorize('create', Project::class);

        return view('projects.create', ['project' => new Project]);
    }

    public function store(StoreProjectRequest $request): RedirectResponse
    {
        $project = $request->user()->projects()->create($this->projectData($request));

        return redirect()->route('projects.show', $project)->with('status', 'Projet créé.');
    }

    public function show(Project $project, ProjectSummaryService $summaryService): View
    {
        $this->authorize('view', $project);

        return view('projects.show', [
            'project' => $project,
            'summary' => $summaryService->summarize($project),
        ]);
    }

    public function edit(Project $project): View
    {
        $this->authorize('update', $project);

        return view('projects.edit', compact('project'));
    }

    public function update(UpdateProjectRequest $request, Project $project): RedirectResponse
    {
        $this->authorize('update', $project);
        $project->update($this->projectData($request));

        return redirect()->route('projects.show', $project)->with('status', 'Projet mis à jour.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $this->authorize('delete', $project);
        $project->delete();

        return redirect()->route('projects.index')->with('status', 'Projet supprimé.');
    }

    /**
     * @return array<string,mixed>
     */
    private function projectData(StoreProjectRequest|UpdateProjectRequest $request): array
    {
        return $request->validated() + [
            'requires_garage' => $request->boolean('requires_garage'),
        ];
    }
}
