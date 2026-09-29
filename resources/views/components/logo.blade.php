@props(['class' => 'h-7', 'textClass' => null, 'tag' => 'span', 'decorative' => false])

{{-- The Recursive Frog lockup: the supplied PNG mark set beside the
     typographic wordmark.

     The PNG is used exactly as delivered — no tracing, no recolouring, no
     re-encoding — so the artwork that was approved is the artwork that ships.
     Because it is a raster carrying its own jade, it cannot inherit
     currentColor the way a traced SVG would, so on dark grounds the wordmark
     carries the contrast and the mark is left at its native colour.

     Pass :decorative="true" where something else already names the element
     (a link with an aria-label, or a button with its own sr-only text), so
     screen readers do not announce the wordmark twice. --}}
<{{ $tag }} {{ $attributes->merge([
        'class' => 'inline-flex items-center gap-2.5 align-middle',
    ]) }} @if ($decorative) aria-hidden="true" @endif>
    <img src="{{ asset('img/recursivefrog-logo.png') }}"
         alt=""
         class="{{ $class }} w-auto shrink-0 select-none"
         width="159"
         height="130"
         decoding="async">

    <span @class([
        'display leading-none tracking-[-0.03em]',
        $textClass ?? 'text-[1.0625rem]',
    ])>RecursiveFrog</span>
</{{ $tag }}>

