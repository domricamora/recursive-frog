<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\View\View;

/** Portfolio index and case studies (plan.md #19). */
class ProjectController extends Controller
{
    public function index(): View
    {
        return $this->publicView('work.index', [
            'title' => 'Our Work',
            'description' => 'Examples of websites, booking platforms, SaaS products and automation systems built by the Recursive Frog technical team.',
        ], [
            'projects' => Project::query()->published()->ordered()->with('features')->get(),
        ]);
    }

    public function show(Project $project): View
    {
        abort_unless($project->published, 404);

        $project->load('features');

        return $this->publicView('work.show', [
            'title' => $project->name,
            'description' => $project->summary,
        ], [
            'project' => $project,
            'related' => Project::query()
                ->published()
                ->ordered()
                ->whereKeyNot($project->getKey())
                ->with('features')
                ->take(3)
                ->get(),
        ]);
    }
}
