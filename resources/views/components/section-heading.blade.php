@props(['title', 'copy' => null, 'align' => 'left', 'size' => 'display-3', 'serif' => false, 'class' => ''])

{{-- A section opener: one confident statement, optional supporting copy. --}}
<header {{ $attributes->merge(['class' => ($align === 'center' ? 'mx-auto max-w-3xl text-center ' : 'max-w-2xl ').trim($class)]) }}>
    <h2 class="{{ $serif ? 'statement '.$size : 'display '.$size }}">{{ $title }}</h2>

    @if ($copy)
        <p class="mt-6 max-w-xl text-[1.0625rem] leading-relaxed text-ink-500 {{ $align === 'center' ? 'mx-auto' : '' }}">
            {!! nl2br(e($copy)) !!}
        </p>
    @endif
</header>

