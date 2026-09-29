<x-layouts.app :seo="$seo">
    <section class="relative overflow-hidden py-32 md:py-44">
        <div class="rf-grid absolute inset-0 opacity-50" aria-hidden="true"></div>
        <div class="rf-glow left-1/2 top-1/3 h-80 w-[38rem] -translate-x-1/2 bg-jade-500/14" aria-hidden="true"></div>

        <div class="shell relative text-center">
            <span class="mx-auto inline-flex h-14 w-14 items-center justify-center rounded-2xl border border-jade-500/30 bg-jade-500/10">
                <svg class="h-6 w-6 text-jade-400" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M5 12.5 9.5 17 19 7.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </span>

            <h1 class="mx-auto mt-8 max-w-3xl text-[2.4rem] leading-[1.02] sm:text-5xl">
                Thanks. We’ve received your message.
            </h1>

            <p class="mx-auto mt-6 max-w-xl text-lg leading-relaxed text-mist-400">
                We’ll review the information you provided and get back to you.
                @if (session('lead_reference'))
                    <span class="mt-2 block font-mono text-xs uppercase tracking-[0.14em] text-mist-500">
                        Reference {{ session('lead_reference') }}
                    </span>
                @endif
            </p>

            <div class="mt-10 flex flex-col items-center justify-center gap-3 sm:flex-row">
                <x-btn :href="route('work.index')" track="thankyou_work" class="w-full sm:w-auto">Explore Our Work</x-btn>
                <x-btn :href="route('home')" variant="ghost" track="thankyou_home" class="w-full sm:w-auto">Back to home</x-btn>
            </div>

            <p class="mt-14 font-mono text-[0.6875rem] uppercase tracking-[0.14em] text-mist-500">
                Questions are answered within one business day
            </p>
        </div>
    </section>
</x-layouts.app>

@push('scripts')
    <script>
        // Conversion signal (plan.md #23 / #37). Fires once on arrival.
        window.dataLayer = window.dataLayer || [];
        window.dataLayer.push({
            event: 'contact_form_submit',
            form_id: 'find-your-tier',
            page_path: window.location.pathname,
        });
    </script>
@endpush
