@props(['class' => 'h-7 w-auto', 'label' => null])

{{-- The full Recursive Frog lockup, inlined from public/img/logo.svg.

     Inlining rather than <img> is what keeps fill="currentColor" meaningful,
     so one asset works on the ink hero, the cream interior pages and the
     admin chrome without needing a second colourway. The intrinsic
     width/height are stripped so CSS owns the size and the aspect ratio
     still holds.

     Regenerate with:
       node storage/app/shot/logo.mjs <source.png> public/img 3 --}}
@php
    $svg = (string) file_get_contents(public_path('img/logo.svg'));
    $svg = preg_replace('/\s(?:width|height)="\d+"/', '', $svg);

    // ->class() returns the whole bag, so pull the merged class value back out
    $svg = preg_replace(
        '/<svg /',
        '<svg class="' . e($attributes->class($class)->get('class')) . '" ',
        $svg,
        1,
    );

    // strip the asset's own labelling, then apply exactly one of our own:
    // a label when the logo is the only wordmark, otherwise decorative
    $svg = str_replace(' aria-label="Recursive Frog"', '', $svg);
    $svg = str_replace(
        ' role="img"',
        $label ? ' role="img" aria-label="' . e($label) . '"' : ' aria-hidden="true"',
        $svg,
    );
@endphp
{!! $svg !!}