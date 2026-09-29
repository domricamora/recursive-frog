<x-layouts.admin :title="$project->exists ? 'Edit '.$project->name : 'New project'">
    @section('content')
        <a href="{{ route('admin.projects.index') }}" class="font-mono text-[0.6875rem] uppercase tracking-[0.14em] text-mist-500 hover:text-jade-300">
            ← All projects
        </a>

        <h1 class="mt-4 font-display text-2xl font-semibold">
            {{ $project->exists ? 'Edit '.$project->name : 'New project' }}
        </h1>

        <form method="POST"
              action="{{ $project->exists ? route('admin.projects.update', $project) : route('admin.projects.store') }}"
              class="mt-8 grid gap-6 lg:grid-cols-[1.4fr_1fr]">
            @csrf
            @if ($project->exists) @method('PUT') @endif

            <div class="space-y-6">
                <section class="panel p-6">
                    <h2 class="font-display text-lg font-semibold">Basics</h2>
                    <div class="mt-5 grid gap-5 sm:grid-cols-2">
                        <x-form-field name="name" label="Name" :value="old('name', $project->name)" placeholder="Cover &amp; Keys" />
                        <x-form-field name="slug" label="URL slug" :value="old('slug', $project->slug)" placeholder="cover-and-keys" />
                        <x-form-field name="client" label="Client" :value="old('client', $project->client)" :required="false" />
                        <x-form-field name="category" label="Category" :value="old('category', $project->category)" placeholder="Booking platform" :required="false" />
                        <x-form-field name="year" label="Year" type="number" :value="old('year', $project->year)" :required="false"
                                      hint="Leave blank if not confirmed." />
                        <x-form-field name="display_order" label="Display order" type="number"
                                      :value="old('display_order', $project->display_order ?? 1)" :required="false" />
                    </div>

                    <div class="mt-5 space-y-5">
                        <x-form-field name="summary" label="Summary" type="textarea" :rows="2"
                                      :value="old('summary', $project->summary)"
                                      hint="One sentence, used on cards and in meta descriptions." />
                        <x-form-field name="overview" label="Overview" type="textarea" :rows="4"
                                      :value="old('overview', $project->overview)" :required="false" />
                        <x-form-field name="business_problem" label="Business problem" type="textarea" :rows="3"
                                      :value="old('business_problem', $project->business_problem)" :required="false" />
                        <x-form-field name="solution" label="Solution" type="textarea" :rows="3"
                                      :value="old('solution', $project->solution)" :required="false" />
                        <x-form-field name="results" label="Verified results" type="textarea" :rows="3"
                                      :value="old('results', $project->results)" :required="false"
                                      hint="Only include figures you can verify. Leave blank otherwise." />
                    </div>
                </section>

                <section class="panel p-6">
                    <h2 class="font-display text-lg font-semibold">Stack and links</h2>
                    <div class="mt-5 space-y-5">
                        <x-form-field name="technologies" label="Technologies" :value="old('technologies', implode(', ', $project->technologies ?? []))" :required="false"
                                      hint="Comma separated, e.g. Laravel, PHP, MySQL" />
                        <x-form-field name="integrations" label="Integrations" :value="old('integrations', implode(', ', $project->integrations ?? []))" :required="false"
                                      hint="Comma separated, e.g. PayMongo, GCash" />
                        <x-form-field name="external_url" label="Live URL" type="url" :value="old('external_url', $project->external_url)" :required="false" />
                    </div>

                    <div class="mt-5 space-y-3">
                        <label class="flex items-center gap-3 text-sm text-mist-300">
                            <input type="checkbox" name="published" value="1" class="h-4 w-4 accent-[#96d410]"
                                   @checked(old('published', $project->published ?? true))>
                            Published on the site
                        </label>
                        <label class="flex items-center gap-3 text-sm text-mist-300">
                            <input type="checkbox" name="featured" value="1" class="h-4 w-4 accent-[#96d410]"
                                   @checked(old('featured', $project->featured))>
                            Show on the homepage
                        </label>
                    </div>
                </section>

                <div class="panel flex flex-wrap items-center gap-3 p-6">
                    <button type="submit" class="btn btn-primary">{{ $project->exists ? 'Save changes' : 'Create project' }}</button>
                    <a href="{{ route('admin.projects.index') }}" class="btn btn-ghost">Cancel</a>
                </div>
            </div>

            <div class="space-y-6">
                <section class="panel p-6">
                    <h2 class="font-display text-lg font-semibold">Key features</h2>

                    @if ($project->exists)
                        <ul class="mt-5 space-y-3">
                            @forelse ($project->features as $feature)
                                <li class="rounded-lg border border-navy-800 bg-navy-850/50 p-4">
                                    <form method="POST" action="{{ route('admin.projects.features.update', [$project, $feature]) }}" class="space-y-3">
                                        @csrf
                                        @method('PUT')
                                        <div class="flex items-start gap-3">
                                            <input class="field" type="text" name="name" value="{{ $feature->name }}" required>
                                            <input class="field w-20 shrink-0" type="number" name="display_order"
                                                   value="{{ $feature->display_order }}" title="Order">
                                        </div>
                                        <textarea class="field min-h-16 resize-y text-sm" name="description"
                                                  placeholder="Short description">{{ $feature->description }}</textarea>
                                        <button type="submit" class="btn btn-ghost btn-sm">Save</button>
                                    </form>

                                    <form method="POST" action="{{ route('admin.projects.features.destroy', [$project, $feature]) }}"
                                          class="mt-2" onsubmit="return confirm('Remove this capability?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="font-mono text-[0.625rem] uppercase tracking-[0.14em] text-rose-ish hover:underline">Remove</button>
                                    </form>
                                </li>
                            @empty
                                <li class="text-sm text-mist-500">No features yet.</li>
                            @endforelse
                        </ul>
                    @else
                        <p class="mt-5 text-sm text-mist-500">Save the project first, then add its features.</p>
                    @endif
                </section>

                @if ($project->exists)
                    <section class="panel p-6">
                        <h2 class="font-display text-lg font-semibold">Add a feature</h2>
                        <form method="POST" action="{{ route('admin.projects.features.store', $project) }}" class="mt-5 space-y-3">
                            @csrf
                            <input class="field" type="text" name="name" placeholder="Book and pay in one flow" required>
                            <textarea class="field min-h-16 resize-y text-sm" name="description" placeholder="Short description"></textarea>
                            <button type="submit" class="btn btn-primary btn-sm">Add feature</button>
                        </form>
                    </section>
                @endif
            </div>
        </form>
    @endsection
</x-layouts.admin>
