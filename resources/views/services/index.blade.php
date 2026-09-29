<x-layouts.app :seo="$seo">
    <x-page-hero
        eyebrow="Services"
        title="Choose the system your clinic needs today."
        copy="Start with the foundation, add software as your operation grows, and introduce automation when it makes sense."
        :crumbs="['Home' => route('home'), 'Services' => null]" />

    <section class="py-16 md:py-20">
        <div class="shell">
            <div class="mt-6 grid gap-6 md:grid-cols-3">
                @foreach ($tiers as $index => $tier)
                    <div class="reveal">
                        <x-tier-card :tier="$tier" :index="$index" :featured="$index === 1" />
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- The progression, drawn out. The tiers are a path, not a price list. --}}
    <section class="border-y border-navy-800 bg-navy-900/60 py-20 md:py-24">
        <div class="shell">
            <x-eyebrow>The progression</x-eyebrow>
            <h2 class="mt-5 max-w-2xl text-3xl leading-[1.05] sm:text-4xl">
                Every tier keeps what the last one built.
            </h2>

            <ol class="mt-14 grid gap-px overflow-hidden rounded-2xl border border-navy-700 bg-navy-700 md:grid-cols-3">
                @foreach ($tiers as $tier)
                    <li class="bg-navy-900 p-8">
                        <div class="flex items-baseline gap-3">
                            <span class="font-display text-4xl font-bold text-jade-400">{{ $tier->level() }}</span>
                            <span class="font-mono text-[0.6875rem] uppercase tracking-[0.14em] text-mist-500">{{ $tier->model }}</span>
                        </div>

                        <h3 class="mt-6 font-display text-xl font-semibold">{{ $tier->includes }}</h3>
                        <p class="mt-3 text-[0.9375rem] leading-relaxed text-mist-400">{{ $tier->short_description }}</p>

                        <a href="{{ route('services.show', $tier) }}"
                           class="mt-7 inline-flex items-center gap-2 font-mono text-[0.6875rem] uppercase tracking-[0.14em] text-jade-400 hover:text-jade-300">
                            Discuss {{ $tier->name }}
                            <span aria-hidden="true">→</span>
                        </a>
                    </li>
                @endforeach
            </ol>

            <p class="mt-8 max-w-2xl text-[0.9375rem] leading-relaxed text-mist-500">
                Package inclusions are still being finalised. Items marked as published on this site are
                confirmed — anything still under review is managed in our admin and only appears here once
                approved.
            </p>
        </div>
    </section>

    <x-cta-band />
</x-layouts.app>
