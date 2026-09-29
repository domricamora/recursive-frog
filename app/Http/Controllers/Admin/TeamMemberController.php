<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\TeamMember;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/** Team profile management (plan.md #26). */
class TeamMemberController extends Controller
{
    public function index(): View
    {
        return view('admin.team.index', [
            'members' => TeamMember::query()->ordered()->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.team.form', ['member' => new TeamMember]);
    }

    public function store(Request $request): RedirectResponse
    {
        $member = TeamMember::create($this->validated($request));

        AuditLog::record('team.created', $member, 'Added team profile: '.$member->role);

        return redirect()->route('admin.team.index')->with('status', 'Profile added.');
    }

    public function edit(TeamMember $teamMember): View
    {
        return view('admin.team.form', ['member' => $teamMember]);
    }

    public function update(Request $request, TeamMember $teamMember): RedirectResponse
    {
        $teamMember->update($this->validated($request));

        AuditLog::record('team.updated', $teamMember, 'Updated team profile: '.$teamMember->role);

        return redirect()->route('admin.team.index')->with('status', 'Profile saved.');
    }

    public function destroy(TeamMember $teamMember): RedirectResponse
    {
        $role = $teamMember->role;

        AuditLog::record('team.deleted', $teamMember, 'Deleted team profile: '.$role);
        $teamMember->delete();

        return back()->with('status', 'Profile removed.');
    }

    /** @return array<string, mixed> */
    protected function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => ['nullable', 'string', 'max:120'],
            'role' => ['required', 'string', 'max:120'],
            'focus' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:4000'],
            'highlights' => ['nullable', 'string', 'max:4000'],
            'photo' => ['nullable', 'string', 'max:255'],
            'is_specialist' => ['nullable', 'boolean'],
            'display_order' => ['required', 'integer', 'min:0', 'max:999'],
            'published' => ['nullable', 'boolean'],
        ]);

        $data['is_specialist'] = $request->boolean('is_specialist');
        $data['published'] = $request->boolean('published');
        $data['highlights'] = array_values(array_filter(array_map('trim', explode("\n", (string) ($data['highlights'] ?? '')))));
        $data['highlights'] = $data['highlights'] ?: null;

        return $data;
    }
}
