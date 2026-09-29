<x-layouts.app :seo="$seo" :breadcrumb-schema="$breadcrumbSchema ?? null">
    <x-page-hero
        :eyebrow="$tier->model"
        :title="$tier->name"
        :copy="$tier->short_description"
        :crumbs="['Home' => route('home'), 'Services' => route('services.index'), $tier->name => null]">
        <x-slot:actions>
            <x-btn :href="route('contact', ['tier' => $tier->slug])" track="tier_hero" class="w-full sm:w-auto">
                {{ $tier->cta_label ?: 'Discuss this tier' }}
            </x-btn>
            <x-btn :href="route('how-it-works')" variant="ghost" track="tier_hero_process" class="w-full sm:w-auto">How it works</x-btn>
        </x-slot:actions>
    </x-page-hero>

    {{-- What you get — confirmed inclusions only (plan.md #17, acceptance #14) --}}
    <section class="py-16 md:py-24">
        <div class="shell">
            <div class="grid gap-12 lg:grid-cols-[1.15fr_0.85fr] lg:gap-20">
                <div>
                    <x-eyebrow>What is included</x-eyebrow>
                    <h2 class="mt-5 text-3xl leading-[1.05] sm:text-4xl">{{ $tier->includes }} for your clinic.</h2>

                    @if ($tier->description)
                        <p class="mt-6 max-w-2xl text-[1.0625rem] leading-relaxed text-mist-400">
                            {!! nl2br(e($tier->description)) !!}
                        </p>
                    @endif

                    @if ($publishedFeatures->isNotEmpty())
                        <ul class="mt-10 divide-y divide-navy-800 border-y border-navy-800">
                            @foreach ($publishedFeatures as $feature)
                                <li class="flex gap-5 py-5">
                                    <span class="mt-0.5 shrink-0 text-jade-400" aria-hidden="true">
                                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="none">
                                            <circle cx="10" cy="10" r="9" stroke="currentColor" stroke-opacity=".35"/>
                                            <path d="M6 10.3 8.8 13 14 7.4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </span>
                                    <div>
                                        @if ($feature->group)
                                            <p class="font-mono text-[0.625rem] uppercase tracking-[0.14em] text-mist-500">{{ $feature->group }}</p>
                                        @endif
                                        <h3 class="font-display text-lg font-semibold">{{ $feature->name }}</h3>
                                        @if ($feature->description)
                                            <p class="mt-1.5 text-[0.9375rem] leading-relaxed text-mist-400">{{ $feature->description }}</p>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <aside class="space-y-6">
                    @if ($tier->audience)
                        <div class="panel p-7">
                            <p class="eyebrow">Who it is for</p>
                            <p class="mt-4 text-[0.9375rem] leading-relaxed text-mist-300">{!! nl2br(e($tier->audience)) !!}</p>
                        </div>
                    @endif

                    @if ($tier->problems_solved)
                        <div class="panel p-7">
                            <p class="eyebrow">What problem it solves</p>
                            <ul class="mt-4 space-y-2.5 text-[0.9375rem] leading-relaxed text-mist-300">
                                @foreach (array_filter(array_map('trim', explode("\n", $tier->problems_solved))) as $problem)
                                    <li class="flex gap-2.5">
                                        <span class="mt-2 h-1 w-1 shrink-0 rounded-full bg-jade-400" aria-hidden="true"></span>
                                        <span>{{ $problem }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if ($tier->example_project)
                        <div class="panel p-7">
                            <p class="eyebrow">Example capability</p>
                            <p class="mt-4 text-[0.9375rem] leading-relaxed text-mist-300">{{ $tier->example_project }}</p>
                        </div>
                    @endif
                </aside>
            </div>
        </div>
    </section>

    {{-- Implementation --}}
    @if ($tier->implementation)
        <section class="border-y border-navy-800 bg-navy-900/60 py-20 md:py-24">
            <div class="shell">
                <x-section-heading title="How implementation works." class="max-w-2xl" />

                <ol class="mt-12 grid gap-px overflow-hidden rounded-2xl border border-navy-700 bg-navy-700 md:grid-cols-2">
                    @foreach (array_filter(array_map('trim', explode("\n", $tier->implementation))) as $i => $step)
                        <li class="reveal bg-navy-900 p-7">
                            <span class="font-mono text-[0.6875rem] tracking-[0.16em] text-jade-400 tnum">{{ str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) }}</span>
                            <p class="mt-4 text-[1rem] leading-relaxed text-mist-200">{{ preg_replace('/^\d+\.\s*/', '', $step) }}</p>
                        </li>
                    @endforeach
                </ol>
            </div>
        </section>
    @endif

    {{-- Other tiers --}}
    @if ($otherTiers->isNotEmpty())
        <section class="py-16 md:py-20">
            <div class="shell">
                <x-eyebrow>Other tiers</x-eyebrow>
                <div class="mt-8 grid gap-6 md:grid-cols-2">
                    @foreach ($otherTiers as $other)
                        <div class="reveal"><x-tier-card :tier="$other" /></div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <x-cta-band
        title="Not sure which tier fits?"
        copy="Tell us where your clinic is today and we will point you at the right starting point." />
</x-layouts.app>
