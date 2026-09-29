@props(['class' => 'h-8 w-8', 'label' => null])

{{-- The Recursive Frog mark alone, straight from the supplied PNG.

     Used where there is no room for the lockup: the footer rail, the menu
     overlay and any square slot. The file is the client's artwork, used
     as-is. The intrinsic 159x130 is kept so the browser reserves the right
     box before the image decodes, which stops the wordmark beside it
     shifting on load. --}}
<img src="{{ asset('img/recursivefrog-logo.png') }}"
     alt="{{ $label }}"
     @if (filled($label)) role="img" @else aria-hidden="true" @endif
     class="{{ $class }} w-auto shrink-0 select-none"
     width="159"
     height="130"
     decoding="async">


