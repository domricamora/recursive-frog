<x-layouts.app :seo="$seo" :breadcrumb-schema="$breadcrumbSchema ?? null">
    <x-page-hero
        eyebrow="About"
        title="Technology that fits the way your business works."
        :crumbs="['Home' => route('home'), 'About' => null]">
        <x-slot:actions>
            <x-btn :href="route('work.index')" variant="outline" track="about_work" class="w-full sm:w-auto">See our work</x-btn>
        </x-slot:actions>
    </x-page-hero>

    {{-- A full-bleed beat between the promise and the detail. --}}
    <x-film-panel clip="ink" height="min-h-[56svh]" scrim="film-scrim-soft">
        <div class="shell-flush relative flex flex-1 flex-col justify-end py-20 md:py-24">
            <div class="max-w-3xl">
                <x-eyebrow>What we believe</x-eyebrow>
                <p class="statement statement-1 mt-6">
                    Your software should fit the way your business already works —
                    not replace it overnight.
                </p>
            </div>
        </div>
    </x-film-panel>

    <section class="py-16 md:py-24">
        <div class="shell">
            <div class="grid gap-12 lg:grid-cols-[0.9fr_1.1fr] lg:gap-20">
                <div>
                    <x-eyebrow>Company story</x-eyebrow>
                    <h2 class="mt-5 text-3xl leading-[1.05] sm:text-4xl">
                        More than a brochure website.
                    </h2>
                    <div class="mt-6 space-y-4 text-[1.0625rem] leading-relaxed text-mist-400">
                        <p>
                            Recursive Frog builds digital systems for businesses that need more than a brochure
                            website. The team combines websites, custom software and automation into a
                            progression that can grow with your operation.
                        </p>
                        <p>
                            The three service tiers meet you where you are: build a useful digital front door,
                            connect the systems behind it, and then automate repeat work — with a person
                            always reviewing what the system does.
                        </p>
                    </div>

                    <dl class="mt-12 grid grid-cols-3 gap-6 border-t border-navy-800 pt-8">
                        @foreach ([
                            ['Cebu City', 'Based in'],
                            ['2', 'People'],
                            ['1 business day', 'Reply time'],
                        ] as [$value, $label])
                            <div>
                                <dt class="sr-only">{{ $label }}</dt>
                                <dd class="font-display text-lg font-semibold text-mist-50">{{ $value }}</dd>
                                <dd class="mt-1 font-mono text-[0.625rem] uppercase tracking-[0.12em] text-mist-500">{{ $label }}</dd>
                            </div>
                        @endforeach
                    </dl>
                </div>

                <div>
                    <x-eyebrow>The people behind the work</x-eyebrow>
                    <h2 class="mt-5 font-display text-2xl font-semibold">Meet Nick and Ralph</h2>

                    <div class="mt-6 grid gap-5 sm:grid-cols-2">
                        @foreach ($profiles as $profile)
                            <article class="reveal panel flex h-full flex-col overflow-hidden">
                                <div class="flex h-56 items-end justify-center overflow-hidden bg-bone-200">
                                    <img
                                        src="{{ asset($profile['photo']) }}"
                                        alt="{{ $profile['alt'] }}"
                                        class="h-full w-full object-contain object-bottom"
                                        loading="lazy"
                                    >
                                </div>

                                <div class="flex flex-1 flex-col p-5">
                                    <p class="font-mono text-[0.625rem] uppercase tracking-[0.12em] text-jade-600">
                                        {{ $profile['role'] }}
                                    </p>
                                    <h3 class="mt-2 font-display text-xl font-semibold">{{ $profile['name'] }}</h3>
                                    <p class="mt-1 text-sm text-ink-500">{{ $profile['focus'] }}</p>

                                    <p class="mt-4 text-sm leading-relaxed text-ink-600">{{ $profile['bio'] }}</p>

                                    <ul class="mt-4 space-y-2.5">
                                        @foreach ($profile['highlights'] as $highlight)
                                            <li class="flex items-start gap-2.5 text-[0.8125rem] leading-relaxed text-ink-600">
                                                <svg class="mt-1 h-3.5 w-3.5 shrink-0 text-jade-600" viewBox="0 0 14 14" fill="none" aria-hidden="true">
                                                    <path d="M2 7.2 5.4 10.6 12 4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                                <span>{{ $highlight }}</span>
                                            </li>
                                        @endforeach
                                    </ul>

                                    @if ($profile['portfolio'])
                                        <a
                                            href="{{ $profile['portfolio'] }}"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-jade-700 underline decoration-jade-500/40 underline-offset-4 transition hover:text-jade-900 focus-visible:text-jade-900"
                                        >
                                            View Nick’s portfolio
                                            <span aria-hidden="true">↗</span>
                                        </a>
                                    @endif

                                    <p class="mt-auto pt-5">
                                        <span class="inline-flex items-center border border-jade-700/20 bg-jade-700/5 px-2.5 py-1 font-mono text-[0.625rem] uppercase tracking-[0.1em] text-jade-700">
                                            DTI registered
                                        </span>
                                    </p>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <x-cta-band />
</x-layouts.app>
