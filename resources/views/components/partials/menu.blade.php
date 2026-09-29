{{-- Full-screen menu overlay, shown from the top chrome. --}}
<div id="site-menu"
     x-show="open"
     x-cloak
     @click.self="open = false"
     x-transition:enter="transition ease-out duration-500"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="film on-film fixed inset-0 z-[70] flex flex-col">

    <img class="film-poster" src="{{ asset('media/poster/systems.jpg') }}" alt="" aria-hidden="true">
    <span class="film-scrim" aria-hidden="true"></span>
    <span class="film-tint" style="--tint: 30%" aria-hidden="true"></span>
    <span class="film-grain" aria-hidden="true"></span>

    <div class="relative flex items-center justify-between px-5 py-5 md:px-10">
        <a href="{{ route('home') }}" class="inline-flex items-center text-white" aria-label="{{ $site['name'] ?? 'Recursive Frog' }} — home">
            <x-logo class="h-7" text-class="text-[1rem] text-white" decorative />
        </a>
        <button type="button" @click="open = false" class="circ-btn" aria-label="Close menu">
            <svg class="h-4 w-4" viewBox="0 0 16 16" fill="none" aria-hidden="true">
                <path d="M4 4l8 8M12 4l-8 8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
            </svg>
        </button>
    </div>

    <nav class="relative flex-1 overflow-y-auto px-5 pb-16 pt-4 md:px-10" aria-label="Site">
        <ul>
            @foreach ($links as $index => $link)
                <li>
                    <a href="{{ $link['url'] }}"
                       @click="open = false"
                       @if (request()->routeIs($link['route'])) aria-current="page" @endif
                       class="group flex items-baseline gap-4 py-1.5 md:gap-6">
                        <span class="meta w-8 shrink-0 text-white/45 tnum">0{{ $index + 1 }}</span>
                        <span class="display display-3 md:display-2 {{ request()->routeIs($link['route']) ? 'text-jade-300' : 'text-white' }} transition-opacity group-hover:opacity-70">
                            {{ $link['label'] }}
                        </span>
                    </a>
                </li>
            @endforeach
        </ul>

        @if (filled($services) && $services->isNotEmpty())
            <div class="mt-10 border-t border-white/20 pt-8">
                <p class="meta text-white/45">Service tiers</p>

                {{-- The tiers get the same treatment as the pricing cards on the
                     services page, scaled down: circled numeral, model chip, the
                     name, what it includes and an arrow. Each tile is one big
                     hit target rather than a bare line of text. --}}
                <ul class="mt-5 grid gap-3 sm:grid-cols-3">
                    @foreach ($services as $tier)
                        <li>
                            <a href="{{ route('services.show', $tier) }}"
                               @click="open = false"
                               data-track-cta="menu_tier"
                               class="group relative flex h-full flex-col overflow-hidden rounded-xl border border-white/20 bg-white/[0.06] p-5 backdrop-blur-sm transition duration-300 ease-out hover:-translate-y-0.5 hover:border-white/45 hover:bg-white/[0.11] focus-visible:border-white/60 focus-visible:outline-none">

                                {{-- The numeral ghosted into the corner, as on the
                                     full-size tier card. --}}
                                <span aria-hidden="true"
                                      class="display pointer-events-none absolute -right-1 -top-4 text-[4.5rem] leading-none text-white/[0.08] transition-colors duration-300 group-hover:text-jade-300/20">
                                    {{ $tier->level() }}
                                </span>

                                <span class="relative flex items-center gap-2">
                                    <span class="circled h-8 w-8 text-[0.6875rem] transition-colors duration-300 group-hover:border-jade-300 group-hover:text-jade-300">{{ $tier->level() }}</span>
                                    @if ($tier->model)
                                        <span class="chip chip-ink">{{ $tier->model }}</span>
                                    @endif
                                </span>

                                <span class="display display-4 relative mt-4 block text-white">{{ $tier->name }}</span>

                                @if ($tier->includes)
                                    <span class="meta relative mt-1.5 block text-white/50">{{ $tier->includes }}</span>
                                @endif

                                <span class="relative mt-4 flex items-center gap-1.5 text-[0.8125rem] font-medium text-white/70 transition-colors duration-300 group-hover:text-jade-300">
                                    Explore
                                    <svg class="h-3.5 w-3.5 transition-transform duration-300 ease-out group-hover:translate-x-1" viewBox="0 0 16 16" fill="none" aria-hidden="true">
                                        <path d="M3 8h9m0 0-3.2-3.2M12 8l-3.2 3.2" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mt-12 flex flex-wrap items-center gap-3">
            <a href="{{ route('contact') }}" @click="open = false" data-track-cta="menu"
               class="btn btn-accent btn-lg">Find Your Tier</a>
            @auth
                <a href="{{ route('admin.dashboard') }}" @click="open = false" class="btn btn-ghost btn-lg">Admin</a>
            @endauth
        </div>
    </nav>
</div>
