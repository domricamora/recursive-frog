@props(['href' => null, 'variant' => 'primary', 'size' => 'md', 'track' => null, 'class' => '', 'type' => null])

@php
    // Accept either "primary" or "btn-primary" so callers can be explicit
    // without ending up with a doubled prefix.
    $variant = ltrim(preg_replace('/^btn-/', '', $variant), '-');

    $classes = implode(' ', array_filter([
        'btn',
        'btn-'.$variant,
        match ($size) {
            'sm' => 'btn-sm',
            'lg' => 'btn-lg',
            default => null,
        },
        $class,
    ]));
@endphp

<{{ $type ? 'button' : 'a' }}
    @if ($type) type="{{ $type }}" @else href="{{ $href }}" @endif
    @if ($track) data-track-cta="{{ $track }}" @endif
    {{ $attributes->merge(['class' => $classes]) }}
>
    {{ $slot }}
</{{ $type ? 'button' : 'a' }}>

