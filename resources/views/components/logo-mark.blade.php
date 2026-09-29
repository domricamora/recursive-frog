@props(['class' => 'h-8 w-8', 'label' => null])

{{-- The Recursive Frog glyph alone, inlined from public/img/logo-mark.svg.

     Used where there is no room for the lockup: the footer rail, the menu
     overlay and any square slot. Inlining keeps fill="currentColor" working
     so the mark recolours with its ground. The viewBox is not square, so a
     square class simply letterboxes it rather than distorting it.

     Regenerate with:
       node storage/app/shot/logo.mjs <source.png> public/img 3 --}}
@php
    $svg = (string) file_get_contents(public_path('img/logo-mark.svg'));
    $svg = preg_replace('/\s(?:width|height)="\d+"/', '', $svg);

    // ->class() returns the whole bag, so pull the merged class value back out
    $svg = preg_replace(
        '/<svg /',
        '<svg class="' . e($attributes->class($class)->get('class')) . '" ',
        $svg,
        1,
    );

    // strip the asset's own labelling, then apply exactly one of our own:
    // a label when the mark is the only wordmark, otherwise decorative
    $svg = str_replace(' aria-label="Recursive Frog"', '', $svg);
    $svg = str_replace(
        ' role="img"',
        $label ? ' role="img" aria-label="' . e($label) . '"' : ' aria-hidden="true"',
        $svg,
    );
@endphp
{!! $svg !!}

