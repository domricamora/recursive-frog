<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Project;
use App\Models\ProjectFeature;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/** Portfolio management (plan.md #26). */
class ProjectController extends Controller
{
    public function index(): View
    {
        return view('admin.projects.index', [
            'projects' => Project::query()->ordered()->with('features')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.projects.form', ['project' => new Project]);
    }

    public function store(Request $request): RedirectResponse
    {
        $project = Project::create($this->validated($request));

        AuditLog::record('project.created', $project, 'Created project '.$project->name.'.');

        return redirect()->route('admin.projects.edit', $project)->with('status', 'Project created.');
    }

    public function edit(Project $project): View
    {
        return view('admin.projects.form', ['project' => $project->load('features')]);
    }

    public function update(Request $request, Project $project): RedirectResponse
    {
        $project->update($this->validated($request, $project));

        AuditLog::record('project.updated', $project, 'Updated project '.$project->name.'.');

        return back()->with('status', 'Project saved.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $name = $project->name;

        AuditLog::record('project.deleted', $project, 'Deleted project '.$name.'.');
        $project->delete();

        return redirect()->route('admin.projects.index')->with('status', "{$name} deleted.");
    }

    /* ------------------------------------------------------------------ */

    public function storeFeature(Request $request, Project $project): RedirectResponse
    {
        $data = $this->featureRules($request);

        $project->features()->create([
            ...$data,
            // As with tiers, an omitted optional order falls back to the next.
            'display_order' => ($data['display_order'] ?? null) ?: $project->features()->max('display_order') + 1,
        ]);

        AuditLog::record('project.feature_created', $project, 'Added capability to '.$project->name.'.');

        return back()->with('status', 'Capability added.');
    }

    public function updateFeature(Request $request, Project $project, ProjectFeature $feature): RedirectResponse
    {
        abort_unless($feature->project_id === $project->getKey(), 404);

        $feature->update($this->featureRules($request));

        AuditLog::record('project.feature_updated', $project, 'Updated capability on '.$project->name.'.');

        return back()->with('status', 'Capability saved.');
    }

    public function destroyFeature(Project $project, ProjectFeature $feature): RedirectResponse
    {
        abort_unless($feature->project_id === $project->getKey(), 404);

        $name = $feature->name;
        $feature->delete();

        AuditLog::record('project.feature_deleted', $project, 'Removed capability "'.$name.'" from '.$project->name.'.');

        return back()->with('status', 'Capability removed.');
    }

    /** @return array<string, mixed> */
    protected function featureRules(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string', 'max:1000'],
            'display_order' => ['nullable', 'integer', 'min:0', 'max:999'],
        ]);
    }

    /** @return array<string, mixed> */
    protected function validated(Request $request, ?Project $project = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:160'],
            'slug' => ['required', 'string', 'max:160', Rule::unique('projects', 'slug')->ignore($project?->id)],
            'client' => ['nullable', 'string', 'max:160'],
            'year' => ['nullable', 'integer', 'min:1990', 'max:'.(date('Y') + 1)],
            'category' => ['nullable', 'string', 'max:120'],
            'summary' => ['required', 'string', 'max:500'],
            'overview' => ['nullable', 'string', 'max:5000'],
            'business_problem' => ['nullable', 'string', 'max:3000'],
            'solution' => ['nullable', 'string', 'max:3000'],
            'results' => ['nullable', 'string', 'max:3000'],
            'technologies' => ['nullable', 'string', 'max:1000'],
            'integrations' => ['nullable', 'string', 'max:1000'],
            'external_url' => ['nullable', 'string', 'max:255'],
            'thumbnail' => ['nullable', 'string', 'max:255'],
            'accent' => ['nullable', 'string', 'max:32'],
            'display_order' => ['required', 'integer', 'min:0', 'max:999'],
            'featured' => ['nullable', 'boolean'],
            'published' => ['nullable', 'boolean'],
        ]);

        $data['slug'] = Str::slug($data['slug']);
        $data['featured'] = $request->boolean('featured');
        $data['published'] = $request->boolean('published');
        $data['technologies'] = $this->toList($data['technologies'] ?? null);
        $data['integrations'] = $this->toList($data['integrations'] ?? null);

        return $data;
    }

    /** @return array<int, string> */
    protected function toList(?string $value): array
    {
        return array_values(array_filter(array_map('trim', explode(',', (string) $value))));
    }
}
