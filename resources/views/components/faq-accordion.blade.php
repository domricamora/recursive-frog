@props(['faqs', 'openFirst' => true])

<div class="divide-y divide-navy-800 border-y border-navy-800">
    @foreach ($faqs as $index => $faq)
        <details class="group" @if ($openFirst && $index === 0) open @endif>
            <summary
                data-track-faq="{{ $faq->question }}"
                class="flex cursor-pointer list-none items-start justify-between gap-6 py-6 marker:hidden [&::-webkit-details-marker]:hidden">
                <span class="flex items-start gap-4">
                    <span class="mt-1 font-mono text-[0.6875rem] text-jade-400/80 tnum">{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</span>
                    <span class="font-display text-lg font-medium leading-snug text-mist-50 sm:text-xl">{{ $faq->question }}</span>
                </span>

                <span class="relative mt-1.5 h-5 w-5 shrink-0" aria-hidden="true">
                    <span class="absolute left-0 top-1/2 h-px w-full -translate-y-1/2 bg-mist-400 transition-colors group-open:bg-jade-400"></span>
                    <span class="absolute left-1/2 top-0 h-full w-px -translate-x-1/2 bg-mist-400 transition-all duration-300 group-open:rotate-90 group-open:opacity-0"></span>
                </span>
            </summary>

            <div class="pb-7 pl-9 pr-8 sm:pl-10">
                <div class="max-w-3xl text-[0.9375rem] leading-relaxed text-mist-400">
                    {!! nl2br(e($faq->answer)) !!}
                </div>
                @if ($faq->category)
                    <p class="mt-4 font-mono text-[0.6875rem] uppercase tracking-[0.14em] text-mist-500">{{ $faq->category }}</p>
                @endif
            </div>
        </details>
    @endforeach
</div>
