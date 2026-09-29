<x-layouts.app :seo="$seo" :film-hero="true" :organisation-schema="$organisationSchema ?? null">

    {{-- ============================================================
         1. Hero — plan.md #6. Full-bleed film, headline hard to the
            left edge, one invitation in the bottom right.
         ============================================================ --}}
    <x-film-panel clip="hero" height="min-h-[100svh] md:min-h-[92svh]" position="center 45%">
        <div class="shell-flush relative flex flex-1 flex-col justify-end pb-14 pt-32 md:pb-20">
            <h1 class="display display-1 max-w-[16ch]">
                <span class="reveal block" data-reveal-order="0">Build.</span>
                <span class="reveal block" data-reveal-order="1">Automate.</span>
                <span class="reveal block text-jade-300" data-reveal-order="2">Scale.</span>
            </h1>

            <div class="mt-10 grid gap-8 border-t border-white/25 pt-8 md:grid-cols-[1.1fr_0.9fr] md:gap-16">
                <p class="reveal max-w-lg text-lg leading-relaxed text-white/85" data-reveal-order="3">
                    {{ config('site.positioning') }} Build a better booking experience, automate
                    repetitive admin work, and give your clinic the systems it needs to grow.
                </p>

                <div class="reveal flex flex-col items-start gap-6 md:items-end" data-reveal-order="4">
                    <a href="{{ route('contact') }}" data-track-cta="hero_primary" class="btn btn-accent btn-lg">
                        Find Your Tier
                        <svg class="h-4 w-4" viewBox="0 0 16 16" fill="none" aria-hidden="true">
                            <path d="M3 8h9m0 0-3.2-3.2M12 8l-3.2 3.2" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                    <a href="{{ route('work.index') }}" data-track-cta="hero_secondary" class="link-rule text-white/85">
                        See Our Work
                    </a>
                </div>
            </div>
        </div>

        {{-- Scroll cue, bottom right. --}}
        <div class="shell-flush relative flex justify-end pb-6">
            <span class="pill inline-flex text-white/80">Scroll
                <svg class="h-3 w-3" viewBox="0 0 12 12" fill="none" aria-hidden="true">
                    <path d="M6 2v7m0 0 2.5-2.5M6 9 3.5 6.5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </span>
        </div>
    </x-film-panel>

    {{-- ============================================================
         2. Approach over film — the second full-bleed panel. Copy
            sits right, a single underlined link closes it.
         ============================================================ --}}
    <x-film-panel clip="systems" height="min-h-[70svh]" scrim="film-scrim-soft">
        <div class="shell-flush relative flex flex-1 flex-col justify-end py-20 md:py-28">
            <div class="ml-auto max-w-2xl">
                <x-eyebrow>The approach</x-eyebrow>
                <p class="display display-3 mt-6">
                    Recursive Frog builds the website, the software behind it and the
                    automation that keeps it running — as one system, owned by your clinic.
                </p>
                <a href="{{ route('how-it-works') }}" data-track-cta="approach" class="link-rule mt-8 inline-flex items-center gap-3 text-white/90">
                    How it works
                    <span class="circ-btn h-9 w-9" aria-hidden="true">
                        <svg class="h-3.5 w-3.5" viewBox="0 0 16 16" fill="none">
                            <path d="M3 8h9m0 0-3.2-3.2M12 8l-3.2 3.2" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                </a>
            </div>
        </div>
    </x-film-panel>

    {{-- ============================================================
         3. The problem — plan.md #7. A large statement on the left,
            a short paragraph and one link on the right.
         ============================================================ --}}
    <section class="bg-bone-50 py-20 md:py-28">
        <div class="shell">
            <div class="grid gap-10 lg:grid-cols-[1fr_0.85fr] lg:gap-20">
                <h2 class="display display-2">
                    Running a clinic is hard enough.
                </h2>

                <div class="lg:pt-3">
                    <p class="text-[1.0625rem] leading-relaxed text-ink-500">
                        Your clinic should not have to depend on scattered messages, paper forms and
                        manual admin work to keep everything moving.
                    </p>
                    <a href="{{ route('contact') }}" data-track-cta="problem" class="link-rule mt-6 inline-block text-ink-900">
                        Talk to Recursive Frog
                    </a>
                </div>
            </div>

            <ul class="mt-16 grid border-t border-ink-900/10 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ([
                    ['01', 'Buried inquiries', 'Inquiries getting buried in Messenger and Viber chats?'],
                    ['02', 'Missed appointments', 'Appointments missed because there are no reminders or deposits?'],
                    ['03', 'Scattered patient history', 'Patient history split between notebooks and paper forms?'],
                    ['04', 'Stock uncertainty', 'Not sure what is in stock until it runs out?'],
                    ['05', 'Limited visibility', 'Finding out your numbers only at month-end?'],
                    ['06', 'One-time clients', 'Clients who visit once and never come back?'],
                ] as $i => [$number, $title, $body])
                    <li class="reveal border-b border-ink-900/10 py-8 sm:pr-8 {{ $i % 3 === 2 ? 'lg:pr-0' : '' }} {{ $i >= 3 ? 'lg:border-t lg:border-ink-900/10' : '' }} lg:[&:nth-child(3n+1)]:pl-0 lg:[&:nth-child(3n)]:pr-8">
                        <span class="circled" aria-hidden="true">{{ $number }}</span>
                        <h3 class="display display-4 mt-5">{{ $title }}</h3>
                        <p class="mt-2.5 text-[0.9375rem] leading-relaxed text-ink-500">{{ $body }}</p>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>

    {{-- ============================================================
         4. Build / Automate / Scale — plan.md #8. Three film cards,
            one per stage, each with its own clip.
         ============================================================ --}}
    <section class="bg-bone-100 py-20 md:py-28">
        <div class="shell">
            <div class="flex flex-col gap-8 md:flex-row md:items-end md:justify-between">
                <x-section-heading title="One clear path from website to digital system." class="max-w-xl" />
                <p class="max-w-sm text-[0.9375rem] leading-relaxed text-ink-500">
                    Start with the website. Add the software. Add the automation. Stop at the
                    level that makes sense right now.
                </p>
            </div>

            <ol class="mt-14 grid gap-6 md:grid-cols-3">
                @foreach ([
                    ['01', 'Build', 'build', 'A website designed to invite bookings, built on a foundation you own outright.'],
                    ['02', 'Automate', 'automate', 'AI and automation for administrative work. A person always stays in charge.'],
                    ['03', 'Scale', 'scale', 'Clinic software that grows as your clinic grows — records, stock, numbers.'],
                ] as [$number, $title, $clip, $body])
                    <li class="card card-lift reveal flex flex-col overflow-hidden">
                        <div class="film relative h-52 shrink-0">
                            <img class="film-poster" src="{{ asset("media/poster/{$clip}.jpg") }}" alt="" width="1280" height="720" loading="lazy" decoding="async">
                            <video data-film class="film-media" autoplay muted loop playsinline preload="none" tabindex="-1">
                                <source src="{{ asset("media/video/{$clip}.mp4") }}" type="video/mp4">
                            </video>
                            <span class="film-scrim-soft absolute inset-0" aria-hidden="true"></span>
                            <span class="on-film absolute inset-0 flex items-end p-6">
                                <span class="display display-3">{{ $title }}</span>
                            </span>
                        </div>
                        <div class="flex flex-1 flex-col p-7">
                            <span class="meta text-ink-400 tnum">Stage {{ $number }}</span>
                            <p class="mt-4 text-[0.9375rem] leading-relaxed text-ink-500">{{ $body }}</p>
                        </div>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    {{-- ============================================================
         5. Editorial statement over ink, and the service tiers —
            plan.md #9. A serif line sets up the three packages.
         ============================================================ --}}
    <section class="bg-ink-950 py-24 text-bone-100 md:py-32">
        <div class="shell">
            <p class="statement statement-1 mx-auto max-w-4xl text-center text-bone-50">
                Three ways to work with us. Each one builds on the last, and each one
                leaves you owning everything at the end.
            </p>

            @if ($tiers->isNotEmpty())
                <div class="mt-20">
                    <x-eyebrow class="text-bone-100/50">Service tiers</x-eyebrow>

                    <div class="mt-10 grid gap-6 md:grid-cols-3">
                        @foreach ($tiers as $tier)
                            <x-tier-card :tier="$tier" />
                        @endforeach
                    </div>

                    <div class="mt-10 flex flex-wrap items-center gap-x-8 gap-y-3">
                        <a href="{{ route('services.index') }}" data-track-cta="tiers_all" class="link-rule text-bone-100">
                            Compare all tiers
                        </a>
                        <p class="meta text-bone-100/45">
                            Package inclusions are confirmed with you before anything is scoped.
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </section>

    {{-- ============================================================
         6. Featured work — plan.md #12. A hairline runs above each
            panel; a counter, the name and a line of meta sit on it.
         ============================================================ --}}
    @if ($featuredProjects->isNotEmpty())
        <section class="bg-bone-50">
            <div class="shell py-20 md:py-28">
                <div class="flex flex-col gap-8 md:flex-row md:items-end md:justify-between">
                    <x-section-heading title="Real platforms. Built by Nick." class="max-w-xl" />
                    <a href="{{ route('work.index') }}" data-track-cta="work_all" class="link-rule shrink-0 text-ink-900">
                        See all projects
                    </a>
                </div>

                <div class="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($featuredProjects as $project)
                        <div class="reveal">
                            <x-project-card :project="$project" />
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ============================================================
         7. Team — plan.md #14. A rail with a circled counter on the
            left and circular arrows on the right.
         ============================================================ --}}
    @if ($team->isNotEmpty())
        <section class="rule-b bg-bone-100 py-20 md:py-28">
            <div class="shell">
                <div>
                    <x-eyebrow :number="str_pad((string) $team->count(), 2, '0', STR_PAD_LEFT)">
                        People
                    </x-eyebrow>
                    <h2 class="display display-2 mt-6 max-w-2xl">
                        A small team that finishes what it starts.
                    </h2>
                </div>

                {{-- No prev/next arrows. The team is small enough that the
                     cards all fit from 1071px up, and below that the rail is
                     a plain overflow scroller driven by touch, trackpad and
                     keyboard. The edge fade in app.css is what signals there
                     is more to scroll to. --}}

                <div class="rail mt-12 -mx-5 px-5 md:-mx-10 md:px-10 xl:-mx-14 xl:px-14">
                    @foreach ($team as $member)
                        <article class="rail-card card flex h-full flex-col p-7">
                            <span class="monogram h-12 w-12 text-[0.8125rem]">{{ $member->initials() }}</span>

                            {{-- A seat can be listed by role before a name is
                                 published, so fall back rather than render a gap. --}}
                            <h3 class="display display-4 mt-6">{{ $member->name ?: $member->role }}</h3>

                            @if ($member->name && $member->role)
                                <p class="meta mt-2 text-ink-400">{{ $member->role }}</p>
                                <p class="sign mt-1">{{ $member->name }}</p>
                            @elseif ($member->focus)
                                <p class="meta mt-2 text-ink-400">{{ $member->focus }}</p>
                            @endif

                            <p class="mt-5 text-[0.9375rem] leading-relaxed text-ink-500">{{ $member->bio }}</p>

                            @if ($member->highlights)
                                <ul class="mt-5 flex flex-wrap gap-2">
                                    @foreach (array_slice($member->highlights, 0, 4) as $highlight)
                                        <li class="chip normal-case tracking-normal">{{ $highlight }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ============================================================
         8. Trust — plan.md #13. Commitments on a hairline grid.
         ============================================================ --}}
    <section class="bg-bone-50 py-20 md:py-28">
        <div class="shell">
            <x-section-heading title="No surprises. No lock-in." class="max-w-2xl" />

            <ul class="mt-14 grid border-t border-ink-900/10 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($commitments as $i => $commitment)
                    <li class="reveal border-b border-ink-900/10 py-8 sm:pr-8 lg:[&:nth-child(3n+1)]:pl-0 lg:[&:nth-child(3n)]:pr-8">
                        <span class="circled" aria-hidden="true">{{ str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) }}</span>
                        <h3 class="display display-4 mt-5">{{ $commitment['title'] }}</h3>
                        <p class="mt-2.5 text-[0.9375rem] leading-relaxed text-ink-500">{{ $commitment['body'] }}</p>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>

    {{-- ============================================================
         9. FAQ preview — plan.md #21.
         ============================================================ --}}
    @if ($faqs->isNotEmpty())
        <section class="border-t border-ink-900/10 bg-bone-100 py-20 md:py-28">
            <div class="shell">
                <div class="grid gap-12 lg:grid-cols-[0.8fr_1.2fr] lg:gap-20">
                    <div>
                        <x-eyebrow number="?">Questions</x-eyebrow>
                        <h2 class="display display-2 mt-6">Straight answers.</h2>
                        <p class="mt-6 text-[1.0625rem] leading-relaxed text-ink-500">
                            If your question is not here, ask it directly — we answer within one
                            business day.
                        </p>
                        <a href="{{ route('faq') }}" data-track-cta="faq_all" class="link-rule mt-7 inline-block text-ink-900">
                            All questions
                        </a>
                    </div>

                    <x-faq-accordion :faqs="$faqs" />
                </div>
            </div>
        </section>
    @endif

    <x-cta-band />
</x-layouts.app>

