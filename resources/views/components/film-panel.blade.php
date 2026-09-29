@props([
    'clip',
    'poster' => null,
    'height' => 'min-h-[86svh]',
    'position' => 'center',
    'scrim' => '',
])

@php
    // Each clip ships as a matched pair written by the encode pipeline.
    $poster ??= "media/poster/{$clip}.jpg";
@endphp

{{--
    A full-bleed film panel.

    The <video> is decorative: it carries no controls, is hidden from assistive
    tech, and is layered behind the poster frame and the scrim. Every word of
    copy lives in the slot, so the panel works as a hero, a quote or a CTA.
--}}
<div {{ $attributes->class(['film on-film flex flex-col justify-end', $height]) }}>
    <img class="film-poster" src="{{ asset($poster) }}" alt="" width="1920" height="1080"
         style="object-position: {{ $position }}" loading="lazy" decoding="async">
    <video
        data-film
        class="film-media"
        autoplay
        muted
        loop
        playsinline
        preload="metadata"
        style="object-position: {{ $position }}"
        tabindex="-1"
    >
        <source src="{{ asset("media/video/{$clip}.mp4") }}" type="video/mp4">
    </video>
    <span class="film-scrim {{ $scrim ?? '' }}" aria-hidden="true"></span>
    <span class="film-grain" aria-hidden="true"></span>

    {{ $slot }}
</div>
