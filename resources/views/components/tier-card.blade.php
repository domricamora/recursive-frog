@props(['tier', 'index' => null, 'featured' => false])

<article @class([
    'card card-lift group relative flex h-full flex-col overflow-hidden p-7 md:p-8',
    'border-ink-900' => $featured,
])>
    {{-- The numeral is the brand: 3, 5 and 7 set large and ghosted in the
         corner, the way a printed price list would carry it. --}}
    <span aria-hidden="true"
          class="display pointer-events-none absolute -right-2 -top-6 text-[6.5rem] leading-none text-ink-900/[0.06] transition-colors duration-500 group-hover:text-jade-500/15">
        {{ $tier->level() }}
    </span>

    <div class="relative">
        <div class="flex flex-wrap items-center gap-2">
            <span class="circled" aria-hidden="true">{{ $tier->level() }}</span>
            <span class="chip chip-accent">{{ $tier->model }}</span>
            @if ($tier->badge)
                <span class="chip">{{ $tier->badge }}</span>
            @endif
        </div>

        <h3 class="display display-4 mt-6">{{ $tier->name }}</h3>

        <p class="meta mt-2 text-ink-400">{{ $tier->includes }}</p>

        <p class="mt-5 text-[0.9375rem] leading-relaxed text-ink-500">{{ $tier->summary }}</p>

        @if ($tier->highlights)
            <ul class="mt-6 space-y-2.5">
                @foreach ($tier->highlights as $highlight)
                    <li class="flex items-start gap-2.5 text-sm text-ink-600">
                        <svg class="mt-[0.3rem] h-3 w-3 shrink-0 text-jade-500" viewBox="0 0 14 14" fill="none" aria-hidden="true">
                            <path d="M2 7.2 5.4 10.6 12 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span>{{ $highlight }}</span>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <div class="relative mt-auto pt-8">
        <a href="{{ route('services.show', $tier) }}"
           data-track-cta="tier_card"
           class="flex items-center justify-between gap-3 border-t border-ink-900/10 pt-5 text-[0.9375rem] font-medium text-ink-900">
            <span>Explore {{ $tier->name }}</span>
            <span class="circ-btn h-9 w-9" aria-hidden="true">
                <svg class="h-3.5 w-3.5" viewBox="0 0 16 16" fill="none">
                    <path d="M3 8h9m0 0-3.2-3.2M12 8l-3.2 3.2" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </span>
        </a>
    </div>
</article>

