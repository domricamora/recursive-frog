@props(['services' => null, 'overlay' => true])

@php
    $links = [
        ['label' => 'Approach', 'route' => 'how-it-works', 'url' => route('how-it-works')],
        ['label' => 'Services', 'route' => 'services.index', 'url' => route('services.index')],
        ['label' => 'Our Work', 'route' => 'work.index', 'url' => route('work.index')],
        ['label' => 'About', 'route' => 'about', 'url' => route('about')],
        ['label' => 'FAQ', 'route' => 'faq', 'url' => route('faq')],
    ];
@endphp

<div x-data="{ open: false }"
     x-effect="document.documentElement.classList.toggle('overflow-hidden', open)"
     @keydown.escape.window="open = false">

    {{-- Top chrome: the clock on the left, the wordmark lozenge in the centre
         and one invitation on the right. It floats over the hero film until the
         page scrolls, then settles into a solid cream bar. --}}
    <header data-chrome
            :data-stuck="'false'"
            class="chrome"
            :class="open || ({{ $overlay ? 'true' : 'false' }} && $el.dataset.stuck !== 'true') ? 'text-white' : 'text-ink-900'">

        {{-- Manila clock. Server-rendered so the pill is never empty before
             hydration, then kept live by app.js. It inherits the chrome
             colour so it stays legible over film and on cream alike. --}}
        <div class="flex justify-start">
            <span data-clock
                  class="pill hidden opacity-80 sm:inline-flex"
                  aria-label="Current time in Cebu City">
                <span data-clock-time class="tnum">{{ now('Asia/Manila')->format('H:i') }}</span>
                <span class="text-[0.5625rem] opacity-60">PHT</span>
            </span>
        </div>

        {{-- The centred lockup doubles as the menu trigger. It sits inside the
             button as plain SVG rather than an <x-logo> link, because a nested
             anchor inside a button is invalid and gets hoisted out of it. The
             mark is decorative here; the button is named by its sr-only text. --}}
        <div class="flex justify-center">
            <button type="button"
                    data-menu-open
                    @click="open = true"
                    :aria-expanded="open"
                    aria-controls="site-menu"
                    class="wordmark-plate">
                <x-logo class="h-7 w-auto" />
                <span class="text-[0.75rem] font-medium leading-none text-ink-400" aria-hidden="true">®</span>
                <span class="flex h-6 w-6 items-center justify-center" aria-hidden="true">
                    <svg class="h-3.5 w-3.5" viewBox="0 0 16 16" fill="none">
                        <path d="M2 5h12M2 11h12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                </span>
                <span class="sr-only">Open menu</span>
            </button>
        </div>

        <div class="flex justify-end">
            <a href="{{ route('contact') }}" data-track-cta="chrome"
               class="link-rule hidden text-[0.9375rem] font-medium sm:inline">Let's talk</a>
            <a href="{{ route('contact') }}" data-track-cta="chrome_mobile"
               class="btn btn-primary btn-sm sm:hidden">Talk</a>
        </div>
    </header>

    @include('components.partials.menu', ['links' => $links, 'services' => $services])
</div>
