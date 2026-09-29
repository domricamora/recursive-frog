@props(['number' => null, 'class' => ''])

{{-- Section marker: a circled counter and a mono label on one hairline. --}}
<div {{ $attributes->merge(['class' => 'inline-flex items-center gap-3 '.trim($class)]) }}>
    @if ($number)
        <span class="circled" aria-hidden="true">{{ $number }}</span>
    @endif
    <span class="eyebrow">{{ $slot }}</span>
</div>

