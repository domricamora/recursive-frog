<x-layouts.admin title="Projects">
    @section('content')
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <h1 class="font-display text-2xl font-semibold">Projects</h1>
                <p class="mt-1 text-sm text-mist-500">Portfolio content is editable without touching code.</p>
            </div>
            <a href="{{ route('admin.projects.create') }}" class="btn btn-primary btn-sm">New project</a>
        </div>

        <div class="panel mt-8 overflow-hidden">
            @if ($projects->isEmpty())
                <p class="p-8 text-sm text-mist-500">No projects yet.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[48rem] text-left text-sm">
                        <thead class="border-b border-navy-800 bg-navy-850/60 font-mono text-[0.625rem] uppercase tracking-[0.14em] text-mist-500">
                            <tr>
                                <th scope="col" class="px-5 py-3 font-normal">Project</th>
                                <th scope="col" class="px-5 py-3 font-normal">Category</th>
                                <th scope="col" class="px-5 py-3 font-normal">Year</th>
                                <th scope="col" class="px-5 py-3 font-normal">Flags</th>
                                <th scope="col" class="px-5 py-3 font-normal"><span class="sr-only">Actions</span></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-navy-800">
                            @foreach ($projects as $project)
                                <tr class="transition-colors hover:bg-navy-850/50">
                                    <td class="px-5 py-4">
                                        <span class="font-medium text-mist-50">{{ $project->name }}</span>
                                        <span class="block font-mono text-[0.625rem] text-mist-500">/{{ $project->slug }}</span>
                                    </td>
                                    <td class="px-5 py-4 text-mist-400">{{ $project->category ?: '—' }}</td>
                                    <td class="px-5 py-4 font-mono text-xs text-mist-500">{{ $project->year ?: '—' }}</td>
                                    <td class="px-5 py-4">
                                        <div class="flex flex-wrap gap-1.5">
                                            <span @class(['chip', 'chip-accent' => $project->published])>{{ $project->published ? 'Published' : 'Draft' }}</span>
                                            @if ($project->featured)
                                                <span class="chip">Featured</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 text-right">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('work.show', $project) }}" target="_blank" rel="noopener" class="btn btn-ghost btn-sm">View</a>
                                            <a href="{{ route('admin.projects.edit', $project) }}" class="btn btn-ghost btn-sm">Edit</a>
                                            <form method="POST" action="{{ route('admin.projects.destroy', $project) }}"
                                                  onsubmit="return confirm('Delete {{ $project->name }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm border border-rose-ish/40 text-rose-ish hover:border-rose-ish">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    @endsection
</x-layouts.admin>
