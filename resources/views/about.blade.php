<x-layouts.app :seo="$seo" :breadcrumb-schema="$breadcrumbSchema ?? null">
    <x-page-hero
        eyebrow="About"
        title="Technology that fits the way your clinic works."
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
                    A clinic’s software should fit the way the clinic already works —
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
                            Recursive Frog builds digital systems for clinics that want more than a brochure
                            website. The company combines websites, software and automation into a
                            progression that can grow with the clinic.
                        </p>
                        <p>
                            That progression is the whole idea behind the three tiers: start with the booking
                            experience, connect the operation behind it, and only then automate the
                            repetitive parts — with a person always approving what the system does.
                        </p>
                    </div>

                    <dl class="mt-12 grid grid-cols-3 gap-6 border-t border-navy-800 pt-8">
                        @foreach ([
                            ['Cebu City', 'Based in'],
                            ['3', 'People'],
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

                <div class="space-y-6">
                    <h2 class="font-display text-2xl font-semibold">The team</h2>

                    <ul class="grid gap-6 sm:grid-cols-3">
                        @foreach ($roles as $member)
                            <li class="reveal panel p-6">
                                <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-navy-600 bg-navy-800 font-mono text-sm text-jade-400">
                                    {{ $member->initials() }}
                                </span>
                                <h3 class="mt-5 font-display text-lg font-semibold">{{ $member->role }}</h3>
                                @if ($member->focus)
                                    <p class="mt-1.5 font-mono text-[0.625rem] uppercase tracking-[0.12em] text-jade-400/80">{{ $member->focus }}</p>
                                @endif
                                <p class="mt-3 text-[0.875rem] leading-relaxed text-mist-400">{{ $member->bio }}</p>
                            </li>
                        @endforeach
                    </ul>

                    @if ($specialist)
                        <div class="reveal panel p-7">
                            <p class="eyebrow">Technical specialist</p>
                            <div class="mt-4 flex items-center gap-4">
                                <span class="inline-flex h-14 w-14 items-center justify-center rounded-2xl border border-jade-500/30 bg-jade-500/10 font-display text-base font-semibold text-jade-300">
                                    {{ $specialist->initials() }}
                                </span>
                                <div>
                                    <h3 class="font-display text-xl font-semibold">{{ $specialist->name }}</h3>
                                    <p class="mt-0.5 text-[0.875rem] text-mist-400">{{ $specialist->focus }}</p>
                                </div>
                            </div>

                            <p class="mt-6 text-[0.9375rem] leading-relaxed text-mist-300">{{ $specialist->bio }}</p>

                            @if ($specialist->highlights)
                                <ul class="mt-6 grid gap-2 sm:grid-cols-2">
                                    @foreach ($specialist->highlights as $highlight)
                                        <li class="flex items-start gap-2.5 text-[0.8125rem] text-mist-400">
                                            <svg class="mt-0.5 h-3.5 w-3.5 shrink-0 text-jade-400" viewBox="0 0 14 14" fill="none" aria-hidden="true">
                                                <path d="M2 7.2 5.4 10.6 12 4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                            <span>{{ $highlight }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <x-cta-band />
</x-layouts.app>
