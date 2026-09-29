<x-layouts.app :seo="$seo" :breadcrumb-schema="$breadcrumbSchema ?? null">
    <x-page-hero
        eyebrow="How it works"
        title="From clinic problems to a working digital system."
        copy="Six stages, in order. You always know what is happening, what we need from you, and what happens next."
        :crumbs="['Home' => route('home'), 'How It Works' => null]" />

    <section class="py-16 md:py-24">
        <div class="shell">
            <ol class="relative space-y-px overflow-hidden rounded-2xl border border-navy-700">
                @foreach ([
                    ['Discovery', 'Understand the current workflow.', 'We map how inquiries, bookings, records, payments and reporting happen today — including the parts that only exist in someone’s head.'],
                    ['Planning', 'Define scope, priorities and the appropriate tier.', 'You get a clear picture of what will be built, what it depends on, and which tier matches. Nothing starts until you agree.'],
                    ['Design', 'Create the user experience and visual direction.', 'Booking journeys, screens and workflows are designed against the real process, not a generic template.'],
                    ['Development', 'Build the website, application or automation.', 'The system is built around your workflow, with weekly written updates and a link to try the latest version.'],
                    ['Testing', 'Test devices, browsers, workflows, forms and integrations.', 'Phones, tablets and computers. Forms, payments, workflows and the edge cases nobody remembers to ask about.'],
                    ['Launch', 'Deploy and provide the agreed support.', 'Your system goes live with continued support. You keep the code and the data.'],
                ] as $i => [$title, $short, $detail])
                    <li class="reveal group relative grid gap-6 bg-navy-900 p-8 transition-colors hover:bg-navy-850 md:grid-cols-[auto_1fr_1.2fr] md:items-baseline md:gap-10">
                        <div class="flex items-center gap-3 md:flex-col md:items-start">
                            <span class="font-display text-3xl font-bold text-navy-700 transition-colors group-hover:text-jade-500/40 tnum">
                                {{ str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) }}
                            </span>
                        </div>

                        <div>
                            <h2 class="font-display text-xl font-semibold">{{ $title }}</h2>
                            <p class="mt-1.5 font-mono text-[0.6875rem] uppercase tracking-[0.12em] text-jade-400/80">{{ $short }}</p>
                        </div>

                        <p class="text-[0.9375rem] leading-relaxed text-mist-400">{{ $detail }}</p>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    {{-- Commitments, repeated deliberately — they are the differentiator. --}}
    <section class="border-y border-navy-800 bg-navy-900/60 py-20 md:py-24">
        <div class="shell">
            <x-section-heading title="What you can count on." class="max-w-2xl" />

            <ul class="mt-12 grid gap-6 md:grid-cols-3">
                @foreach (config('site.commitments') as $commitment)
                    <li class="reveal panel p-7">
                        <h3 class="font-display text-lg font-semibold">{{ $commitment['title'] }}</h3>
                        <p class="mt-2 text-[0.9375rem] leading-relaxed text-mist-400">{{ $commitment['body'] }}</p>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>

    <x-cta-band
        title="Ready to start with discovery?"
        copy="A short conversation is usually enough to tell you which tier makes sense and what it would take." />
</x-layouts.app>
