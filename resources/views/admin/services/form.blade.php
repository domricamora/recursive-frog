<x-layouts.admin :title="$tier->exists ? 'Edit '.$tier->name : 'New tier'">
    @section('content')
        <a href="{{ route('admin.services.index') }}" class="font-mono text-[0.6875rem] uppercase tracking-[0.14em] text-mist-500 hover:text-jade-300">
            ← All tiers
        </a>

        <h1 class="mt-4 font-display text-2xl font-semibold">
            {{ $tier->exists ? 'Edit '.$tier->name : 'New service tier' }}
        </h1>

        <form method="POST"
              action="{{ $tier->exists ? route('admin.services.update', $tier) : route('admin.services.store') }}"
              class="mt-8 grid gap-6 lg:grid-cols-[1.4fr_1fr]">
            @csrf
            @if ($tier->exists) @method('PUT') @endif

            <div class="space-y-6">
                <section class="panel p-6">
                    <h2 class="font-display text-lg font-semibold">Basics</h2>
                    <div class="mt-5 grid gap-5 sm:grid-cols-2">
                        <x-form-field name="name" label="Name" :value="old('name', $tier->name)" placeholder="Recursive 3" />
                        <x-form-field name="slug" label="URL slug" :value="old('slug', $tier->slug)" placeholder="recursive-3" />
                        <x-form-field name="model" label="Model" :value="old('model', $tier->model)" placeholder="Basic model" :required="false" />
                        <x-form-field name="includes" label="Includes" :value="old('includes', $tier->includes)" placeholder="Website" :required="false" />
                        <x-form-field name="badge" label="Badge" :value="old('badge', $tier->badge)" placeholder="Entry" :required="false" />
                        <x-form-field name="display_order" label="Display order" type="number"
                                      :value="old('display_order', $tier->display_order ?? 1)" :required="false" />
                    </div>

                    <div class="mt-5 space-y-5">
                        <x-form-field name="summary" label="Card summary" type="textarea" :rows="3"
                                      :value="old('summary', $tier->summary)" :required="false" />
                        <x-form-field name="short_description" label="Short description" type="textarea" :rows="3"
                                      :value="old('short_description', $tier->short_description)" :required="false" />
                        <x-form-field name="description" label="Full description" type="textarea" :rows="5"
                                      :value="old('description', $tier->description)" :required="false" />
                    </div>
                </section>

                <section class="panel p-6">
                    <h2 class="font-display text-lg font-semibold">Detail page sections</h2>
                    <p class="mt-1 text-xs text-mist-500">Each line in these fields becomes a list item on the public page.</p>
                    <div class="mt-5 space-y-5">
                        <x-form-field name="audience" label="Who it is for" type="textarea" :rows="4"
                                      :value="old('audience', $tier->audience)" :required="false" />
                        <x-form-field name="problems_solved" label="What problem it solves" type="textarea" :rows="4"
                                      :value="old('problems_solved', $tier->problems_solved)" :required="false" />
                        <x-form-field name="implementation" label="How implementation works" type="textarea" :rows="5"
                                      :value="old('implementation', $tier->implementation)" :required="false" />
                        <x-form-field name="example_project" label="Example project / capability" type="textarea" :rows="3"
                                      :value="old('example_project', $tier->example_project)" :required="false" />
                    </div>
                </section>

                <section class="panel p-6">
                    <h2 class="font-display text-lg font-semibold">CTA, chips and visibility</h2>
                    <div class="mt-5 grid gap-5 sm:grid-cols-2">
                        <x-form-field name="cta_label" label="CTA label" :value="old('cta_label', $tier->cta_label)" :required="false" />
                        <x-form-field name="cta_url" label="CTA URL override" :value="old('cta_url', $tier->cta_url)" :required="false" />
                    </div>

                    <div class="mt-5">
                        <label class="label" for="highlights">Highlights (one per line)</label>
                        <textarea class="field min-h-28 resize-y" id="highlights" name="highlights[]"
                                  placeholder="Booking-focused&#10;You own it">@foreach (old('highlights', $tier->highlights ?? []) as $highlight){{ $highlight }}
@endforeach</textarea>
                    </div>

                    <div class="mt-5 space-y-3">
                        <label class="flex items-center gap-3 text-sm text-mist-300">
                            <input type="checkbox" name="is_active" value="1" class="h-4 w-4 accent-[#96d410]"
                                   @checked(old('is_active', $tier->is_active ?? true))>
                            Visible on the public site
                        </label>
                        <label class="flex items-center gap-3 text-sm text-mist-300">
                            <input type="checkbox" name="pricing_published" value="1" class="h-4 w-4 accent-[#96d410]"
                                   @checked(old('pricing_published', $tier->pricing_published))>
                            Pricing approved for publication
                        </label>
                    </div>

                    @if ($tier->pricing_published)
                        <div class="mt-4">
                            <x-form-field name="price_label" label="Price label" :value="old('price_label', $tier->price_label)" :required="false" />
                        </div>
                    @endif
                </section>

                <div class="panel flex flex-wrap items-center gap-3 p-6">
                    <button type="submit" class="btn btn-primary">{{ $tier->exists ? 'Save changes' : 'Create tier' }}</button>
                    <a href="{{ route('admin.services.index') }}" class="btn btn-ghost">Cancel</a>
                    @if ($tier->exists)
                        <a href="{{ route('services.show', $tier) }}" target="_blank" rel="noopener" class="ml-auto font-mono text-[0.6875rem] uppercase tracking-[0.14em] text-jade-400 hover:text-jade-300">
                            Preview page →
                        </a>
                    @endif
                </div>
            </div>

            {{-- Features --}}
            <div class="space-y-6">
                <section class="panel p-6">
                    <h2 class="font-display text-lg font-semibold">Features</h2>
                    <p class="mt-1 text-xs text-mist-500">
                        Only <span class="chip chip-accent">Published</span> items appear on the public page.
                    </p>

                    @if ($tier->exists)
                        <ul class="mt-5 space-y-3">
                            @forelse ($tier->features as $feature)
                                <li class="rounded-lg border border-navy-800 bg-navy-850/50 p-4">
                                    <form method="POST" action="{{ route('admin.services.features.update', [$tier, $feature]) }}" class="space-y-3">
                                        @csrf
                                        @method('PUT')

                                        <div class="flex items-start gap-3">
                                            <input class="field" type="text" name="name" value="{{ $feature->name }}" required>
                                            <input class="field w-20 shrink-0" type="number" name="display_order"
                                                   value="{{ $feature->display_order }}" title="Order">
                                        </div>

                                        <textarea class="field min-h-16 resize-y text-sm" name="description"
                                                  placeholder="Short description">{{ $feature->description }}</textarea>

                                        <div class="flex gap-2">
                                            <input class="field" type="text" name="group" value="{{ $feature->group }}"
                                                   placeholder="Group (e.g. Website)">
                                            <select class="field w-40 shrink-0" name="status">
                                                @foreach (['draft' => 'Draft', 'confirmed' => 'Published', 'archived' => 'Archived'] as $value => $label)
                                                    <option value="{{ $value }}" @selected($feature->status === $value)>{{ $label }}</option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="btn btn-ghost btn-sm shrink-0">Save</button>
                                        </div>
                                    </form>

                                    <form method="POST" action="{{ route('admin.services.features.destroy', [$tier, $feature]) }}"
                                          class="mt-2" onsubmit="return confirm('Remove this feature?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="font-mono text-[0.625rem] uppercase tracking-[0.14em] text-rose-ish hover:underline">
                                            Remove
                                        </button>
                                    </form>
                                </li>
                            @empty
                                <li class="text-sm text-mist-500">No features yet. Add the first one below.</li>
                            @endforelse
                        </ul>
                    @else
                        <p class="mt-5 text-sm text-mist-500">Save the tier first, then add its features.</p>
                    @endif
                </section>

                @if ($tier->exists)
                    <section class="panel p-6">
                        <h2 class="font-display text-lg font-semibold">Add a feature</h2>
                        <form method="POST" action="{{ route('admin.services.features.store', $tier) }}" class="mt-5 space-y-3">
                            @csrf
                            <input class="field" type="text" name="name" placeholder="Feature name" required>
                            <textarea class="field min-h-16 resize-y text-sm" name="description" placeholder="Short description"></textarea>
                            <div class="flex gap-2">
                                <input class="field" type="text" name="group" placeholder="Group">
                                <select class="field w-40 shrink-0" name="status">
                                    <option value="draft" selected>Draft</option>
                                    <option value="confirmed">Published</option>
                                    <option value="archived">Archived</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">Add feature</button>
                        </form>
                    </section>
                @endif
            </div>
        </form>
    @endsection
</x-layouts.admin>
