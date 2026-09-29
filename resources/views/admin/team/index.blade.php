<x-layouts.admin title="Team">
    @section('content')
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <h1 class="font-display text-2xl font-semibold">Team</h1>
                <p class="mt-1 text-sm text-mist-500">Roles shown on the homepage and About page.</p>
            </div>
            <a href="{{ route('admin.team.create') }}" class="btn btn-primary btn-sm">New profile</a>
        </div>

        <ul class="mt-8 grid gap-5 sm:grid-cols-2">
            @forelse ($members as $member)
                <li class="panel p-6">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h2 class="font-display text-lg font-semibold">
                                {{ $member->name ?: $member->role }}
                            </h2>
                            <p class="mt-1 text-sm text-jade-400/90">{{ $member->role }}</p>
                            @if ($member->focus)
                                <p class="mt-1 text-xs text-mist-500">{{ $member->focus }}</p>
                            @endif
                        </div>
                        <div class="flex flex-wrap justify-end gap-1.5">
                            <span @class(['chip', 'chip-accent' => $member->published])>{{ $member->published ? 'Published' : 'Draft' }}</span>
                            @if ($member->is_specialist)
                                <span class="chip">Specialist</span>
                            @endif
                        </div>
                    </div>

                    @if ($member->bio)
                        <p class="mt-4 line-clamp-3 text-sm text-mist-400">{{ $member->bio }}</p>
                    @endif

                    <div class="mt-5 flex gap-2">
                        <a href="{{ route('admin.team.edit', $member) }}" class="btn btn-ghost btn-sm">Edit</a>
                        <form method="POST" action="{{ route('admin.team.destroy', $member) }}"
                              onsubmit="return confirm('Delete this profile?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm border border-rose-ish/40 text-rose-ish hover:border-rose-ish">Delete</button>
                        </form>
                    </div>
                </li>
            @empty
                <li class="panel p-8 text-sm text-mist-500">No profiles yet.</li>
            @endforelse
        </ul>
    @endsection
</x-layouts.admin>
