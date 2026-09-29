@props(['project', 'index' => null])

<article class="card group relative flex h-full flex-col overflow-hidden">
    {{-- Real screenshots where one exists, with the abstract "system map"
         kept as the fallback so a project without artwork still looks
         deliberate. --}}
    <div class="relative h-44 shrink-0 overflow-hidden border-b border-ink-900/10 bg-bone-200">
        @if ($project->thumbnail)
            <img
                src="{{ asset($project->thumbnail) }}"
                alt="Screenshot of {{ $project->name }}"
                width="1200"
                height="750"
                loading="lazy"
                decoding="async"
                class="absolute inset-0 h-full w-full object-cover transition-transform duration-500 ease-out group-hover:scale-[1.04] motion-reduce:transition-none motion-reduce:group-hover:scale-100"
            >
            {{-- A top-weighted scrim rather than the full-bleed film scrim: the
                 film scrim darkens the whole frame, which drains the colour
                 out of real screenshots. This only covers the chip row and
                 fades out before the artwork reads. --}}
            <span
                class="pointer-events-none absolute inset-x-0 top-0 h-24 bg-gradient-to-b from-ink-950/70 via-ink-950/25 to-transparent"
                aria-hidden="true"
            ></span>
        @else
            <div class="absolute inset-0" aria-hidden="true">
                <div class="rf-grid absolute inset-0 opacity-70"></div>
                <div class="rf-glow right-[-3rem] top-[-4rem] h-40 w-56 bg-jade-400/20"></div>

                <svg class="absolute inset-0 h-full w-full" viewBox="0 0 400 176" fill="none">
                    <path d="M40 118 C 120 118, 130 44, 210 56 S 320 126, 366 50" stroke="var(--color-ink-900)" stroke-opacity=".28" stroke-width="1.2"/>
                    <path d="M40 58 C 110 58, 140 132, 214 114 S 320 48, 366 118" stroke="var(--color-jade-500)" stroke-opacity=".55" stroke-width="1.2"/>
                    <circle cx="40" cy="118" r="4" fill="var(--color-ink-900)"/>
                    <circle cx="40" cy="58" r="3" fill="var(--color-ink-900)" fill-opacity=".45"/>
                    <circle cx="210" cy="56" r="3" fill="var(--color-ink-900)" fill-opacity=".45"/>
                    <circle cx="214" cy="114" r="4" fill="var(--color-jade-500)"/>
                    <circle cx="366" cy="50" r="4" fill="var(--color-ink-900)"/>
                    <circle cx="366" cy="118" r="3" fill="var(--color-ink-900)" fill-opacity=".45"/>
                </svg>
            </div>
        @endif

        <div class="absolute left-4 top-4 flex flex-wrap gap-2">
            {{-- chip-ink, because the chips now sit on arbitrary artwork
                 rather than the light bone-200 the default chip was drawn for. --}}
            <span class="chip chip-ink">{{ $project->category }}</span>
            @if ($project->year)
                <span class="chip chip-ink tnum">{{ $project->year }}</span>
            @endif
        </div>
    </div>

    <div class="flex flex-1 flex-col p-6">
        <h3 class="display display-4">
            <a href="{{ route('work.show', $project) }}" class="before:absolute before:inset-0" data-track-cta="portfolio_card">
                {{ $project->name }}
            </a>
        </h3>

        <p class="mt-3 text-[0.9375rem] leading-relaxed text-ink-500">{{ $project->summary }}</p>

        @if ($project->features->isNotEmpty())
            <ul class="mt-5 space-y-2">
                @foreach ($project->features->take(3) as $feature)
                    <li class="flex items-start gap-2 text-[0.8125rem] text-ink-400">
                        <span class="mt-[0.45rem] h-1 w-1 shrink-0 rounded-full bg-jade-500" aria-hidden="true"></span>
                        <span>{{ $feature->name }}</span>
                    </li>
                @endforeach
            </ul>
        @endif

        <p class="meta mt-auto flex items-center gap-2 pt-6 text-ink-400 transition-colors group-hover:text-ink-900">
            View project
            <svg class="h-3 w-3 transition-transform duration-300 group-hover:translate-x-1" viewBox="0 0 16 16" fill="none" aria-hidden="true">
                <path d="M3 8h9m0 0-3.2-3.2M12 8l-3.2 3.2" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </p>
    </div>
</article>

