@props([
    'name',
    'label',
    'type' => 'text',
    'placeholder' => null,
    'value' => null,
    'hint' => null,
    'required' => true,
    'span' => false,
    'rows' => null,
])

@php
    $id = str_replace('_', '-', $name);
    $hasError = $errors->has($name);
    $classes = 'field'.($span ? ' sm:col-span-2' : '').($hasError ? ' border-rose-ish' : '');
@endphp

<div @class(['sm:col-span-2' => $span])>
    <label class="label" for="{{ $id }}">
        {{ $label }}@if ($required)<span class="text-jade-400">*</span>@endif
    </label>

    @if ($type === 'textarea')
        <textarea class="{{ $classes }} min-h-28 resize-y"
                  id="{{ $id }}" name="{{ $name }}" rows="{{ $rows ?? 4 }}"
                  placeholder="{{ $placeholder }}"
                  @if ($required) required @endif
                  @if ($hasError) aria-invalid="true" aria-describedby="{{ $id }}-error" @endif
        >{{ $value }}</textarea>
    @else
        <input class="{{ $classes }}" type="{{ $type }}" id="{{ $id }}" name="{{ $name }}"
               value="{{ $value }}" placeholder="{{ $placeholder }}"
               @if ($required) required @endif
               @if ($hasError) aria-invalid="true" aria-describedby="{{ $id }}-error" @endif>
    @endif

    @if ($hint)
        <p class="mt-1.5 text-xs text-mist-500">{{ $hint }}</p>
    @endif

    @error($name)
        <span class="error" id="{{ $id }}-error">{{ $message }}</span>
    @enderror
</div>
