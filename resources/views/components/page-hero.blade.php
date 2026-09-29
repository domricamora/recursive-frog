@props(['eyebrow' => null, 'title', 'copy' => null, 'crumbs' => []])

{{-- Interior page opener. Deliberately quiet: the page's own headline is the
     only large thing on screen, and the chrome sits on cream rather than film. --}}
<section class="rule-b bg-bone-100 pt-32 pb-14 md:pt-40 md:pb-20">
    <div class="shell">
        @if ($crumbs)
            <nav aria-label="Breadcrumb" class="mb-8">
                <ol class="meta flex flex-wrap items-center gap-2 text-ink-400">
                    @foreach ($crumbs as $label => $url)
                        <li class="flex items-center gap-2">
                            @unless ($loop->first)
                                <span aria-hidden="true" class="opacity-50">/</span>
                            @endunless
                            @if ($url)
                                <a href="{{ $url }}" class="link-rule">{{ $label }}</a>
                            @else
                                <span class="text-ink-900" aria-current="page">{{ $label }}</span>
                            @endif
                        </li>
                    @endforeach
                </ol>
            </nav>
        @endif

        @if ($eyebrow)
            <x-eyebrow>{{ $eyebrow }}</x-eyebrow>
        @endif

        <h1 class="display display-2 mt-6 max-w-4xl">{{ $title }}</h1>

        @if ($copy)
            <p class="mt-7 max-w-2xl text-lg leading-relaxed text-ink-500">{!! nl2br(e($copy)) !!}</p>
        @endif

        @isset($actions)
            <div class="mt-9 flex flex-col gap-3 sm:flex-row">
                {{ $actions }}
            </div>
        @endisset
    </div>
</section>
