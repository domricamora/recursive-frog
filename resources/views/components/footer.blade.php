@props(['site'])

<footer class="relative overflow-hidden bg-ink-950 text-bone-200">
    <div class="mx-auto max-w-[112rem] px-5 md:px-10 xl:px-14">

        {{-- The wordmark runs up the left edge against a ruler that measures
             how far the reader has travelled. --}}
        <div class="flex">
            <div class="hidden shrink-0 items-stretch gap-6 lg:flex xl:gap-10">
                <div class="flex flex-col items-center gap-6 py-14">
                    <a href="{{ route('home') }}" class="text-bone-100 transition-opacity hover:opacity-70" aria-label="{{ $site['name'] }} — home">
                        <x-logo-mark class="h-10 w-10" />
                    </a>

                    {{-- writing-mode rotates the element's own axes, so it will
                         not stretch as a flex item; centre it in a column
                         instead of relying on align-items. --}}
                    <div class="flex flex-1 items-center">
                        <span class="vertical-mark display text-[3rem] text-bone-100 xl:text-[3.5rem]">RecursiveFrog</span>
                    </div>
                </div>
                <span class="ruler my-14 text-bone-100/50" aria-hidden="true"></span>
            </div>

            <div class="flex-1 min-w-0 py-14 lg:pl-14 xl:pl-20">

                <div class="grid gap-12 md:grid-cols-3 md:gap-8">
                    <div>
                        <p class="meta text-bone-100/45">Find us</p>
                        <address class="mt-4 text-[0.9375rem] not-italic leading-relaxed text-bone-200/90">
                            {{ $site['address'] ?: $site['city'] }}
                        </address>

                        <p class="meta mt-8 text-bone-100/45">Email</p>
                        <a href="mailto:{{ $site['email'] }}"
                           class="link-rule mt-2 inline-block text-[0.9375rem]">{{ $site['email'] }}</a>

                        <p class="meta mt-8 text-bone-100/45">Phone</p>
                        <a href="tel:{{ preg_replace('/[^\d+]/', '', $site['phone']) }}"
                           class="link-rule mt-2 inline-block text-[0.9375rem]">{{ $site['phone'] }}</a>

                        @if (filled($site['social']))
                            <p class="meta mt-8 text-bone-100/45">Follow us</p>
                            <ul class="mt-3 flex flex-wrap gap-x-5 gap-y-2">
                                @foreach ($site['social'] as $network => $url)
                                    <li>
                                        <a href="{{ $url }}" rel="noopener noreferrer" target="_blank"
                                           class="link-rule text-[0.9375rem] capitalize">{{ $network }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>

                    <nav aria-label="Explore">
                        <p class="meta text-bone-100/45">Explore</p>
                        <ul class="mt-4 space-y-1">
                            @foreach ([
                                ['Approach', 'how-it-works'],
                                ['Services', 'services.index'],
                                ['Our Work', 'work.index'],
                                ['About', 'about'],
                                ['FAQ', 'faq'],
                                ['Contact', 'contact'],
                            ] as [$label, $routeName])
                                <li>
                                    <a href="{{ route($routeName) }}"
                                       @if (request()->routeIs($routeName)) aria-current="page" @endif
                                       class="display display-4 text-bone-100 transition-opacity hover:opacity-60">
                                        {{ $label }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </nav>

                    <div>
                        <p class="meta text-bone-100/45">Positioning</p>
                        <p class="mt-4 max-w-xs text-[0.9375rem] leading-relaxed text-bone-200/80">
                            {{ $site['tagline'] }} — {{ config('site.positioning') }}
                        </p>

                        {{-- Live read of how far the visitor has scrolled. --}}
                        <div class="mt-10 flex items-center gap-4">
                            <img class="h-11 w-16 rounded-sm object-cover opacity-80"
                                 src="{{ asset('media/poster/hero.jpg') }}" alt="" loading="lazy" width="160" height="96">
                            <p class="text-[0.8125rem] leading-snug text-bone-200/70">
                                You've scrolled
                                <span data-scroll-depth class="text-bone-100">
                                    <span data-scroll-depth-value class="tnum">0m</span>
                                </span><br>
                                and read the entire <a href="{{ route('about') }}"
                                    class="link-rule text-bone-100">method</a>.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mt-16 border-t border-bone-100/15 pt-8">
                    <p class="max-w-xl text-[0.8125rem] leading-relaxed text-bone-200/55">
                        AI does admin only. AI does not diagnose, recommend treatments or make clinical decisions.
                    </p>

                    <ul class="mt-6 flex flex-wrap gap-x-5 gap-y-1 text-[0.8125rem] text-bone-200/60">
                        @foreach (['Privacy Policy', 'Terms of Service', 'Cookies'] as $legal)
                            <li><a href="{{ route('contact') }}" class="link-rule">{{ $legal }}</a></li>
                        @endforeach
                    </ul>

                    <p class="meta mt-10 text-bone-100/40">
                        © {{ now()->year }} {{ $site['name'] }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</footer>
