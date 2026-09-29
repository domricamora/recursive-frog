<x-layouts.app :seo="$seo">
    <x-page-hero
        eyebrow="{{ $project->category }}"
        :title="$project->name"
        :copy="$project->summary"
        :crumbs="['Home' => route('home'), 'Our Work' => route('work.index'), $project->name => null]">
        <x-slot:actions>
            @if ($project->external_url)
                <x-btn :href="$project->external_url" variant="ghost" track="project_external" class="w-full sm:w-auto">
                    Visit the live site
                    <svg class="h-3.5 w-3.5" viewBox="0 0 16 16" fill="none" aria-hidden="true">
                        <path d="M6 3h7v7M13 3 5.5 10.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </x-btn>
            @endif
            <x-btn :href="route('contact')" track="project_cta" class="w-full sm:w-auto">Build something similar</x-btn>
        </x-slot:actions>
    </x-page-hero>

    {{-- Case study template from plan.md #19. No invented numbers. --}}
    <section class="py-16 md:py-24">
        <div class="shell">
            <div class="grid gap-12 lg:grid-cols-[1.2fr_0.8fr] lg:gap-20">
                <div class="space-y-12">
                    @if ($project->overview)
                        <div>
                            <x-eyebrow>Overview</x-eyebrow>
                            <p class="mt-5 text-[1.0625rem] leading-relaxed text-mist-300">{!! nl2br(e($project->overview)) !!}</p>
                        </div>
                    @endif

                    @if ($project->business_problem)
                        <div>
                            <x-eyebrow>Business problem</x-eyebrow>
                            <p class="mt-5 text-[1.0625rem] leading-relaxed text-mist-300">{!! nl2br(e($project->business_problem)) !!}</p>
                        </div>
                    @endif

                    @if ($project->solution)
                        <div>
                            <x-eyebrow>Solution</x-eyebrow>
                            <p class="mt-5 text-[1.0625rem] leading-relaxed text-mist-300">{!! nl2br(e($project->solution)) !!}</p>
                        </div>
                    @endif

                    @if ($project->results)
                        <div>
                            <x-eyebrow>Results</x-eyebrow>
                            <p class="mt-5 text-[1.0625rem] leading-relaxed text-mist-300">{!! nl2br(e($project->results)) !!}</p>
                        </div>
                    @endif
                </div>

                <aside class="space-y-6">
                    <div class="panel p-7">
                        <p class="eyebrow">Project</p>
                        <dl class="mt-5 space-y-4 text-[0.9375rem]">
                            @if ($project->client)
                                <div>
                                    <dt class="font-mono text-[0.625rem] uppercase tracking-[0.14em] text-mist-500">Client</dt>
                                    <dd class="mt-1 text-mist-200">{{ $project->client }}</dd>
                                </div>
                            @endif
                            @if ($project->year)
                                <div>
                                    <dt class="font-mono text-[0.625rem] uppercase tracking-[0.14em] text-mist-500">Year</dt>
                                    <dd class="mt-1 text-mist-200 tnum">{{ $project->year }}</dd>
                                </div>
                            @endif
                            <div>
                                <dt class="font-mono text-[0.625rem] uppercase tracking-[0.14em] text-mist-500">Category</dt>
                                <dd class="mt-1 text-mist-200">{{ $project->category ?: '—' }}</dd>
                            </div>
                        </dl>
                    </div>

                    @if ($project->technologies)
                        <div class="panel p-7">
                            <p class="eyebrow">Technologies</p>
                            <ul class="mt-4 flex flex-wrap gap-2">
                                @foreach ($project->technologies as $tech)
                                    <li class="chip normal-case tracking-normal">{{ $tech }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if ($project->integrations)
                        <div class="panel p-7">
                            <p class="eyebrow">Integrations</p>
                            <ul class="mt-4 flex flex-wrap gap-2">
                                @foreach ($project->integrations as $integration)
                                    <li class="chip chip-accent normal-case tracking-normal">{{ $integration }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </aside>
            </div>

            @if ($project->features->isNotEmpty())
                <div class="mt-20">
                    <x-eyebrow>Key features</x-eyebrow>
                    <ul class="mt-8 grid gap-px overflow-hidden rounded-2xl border border-navy-700 bg-navy-700 md:grid-cols-2">
                        @foreach ($project->features as $feature)
                            <li class="reveal bg-navy-900 p-7">
                                <h2 class="font-display text-lg font-semibold">{{ $feature->name }}</h2>
                                @if ($feature->description)
                                    <p class="mt-2 text-[0.9375rem] leading-relaxed text-mist-400">{{ $feature->description }}</p>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </section>

    @if ($related->isNotEmpty())
        <section class="border-t border-navy-800 py-16 md:py-20">
            <div class="shell">
                <x-eyebrow>More work</x-eyebrow>
                <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($related as $item)
                        <div class="reveal"><x-project-card :project="$item" /></div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</x-layouts.app>
