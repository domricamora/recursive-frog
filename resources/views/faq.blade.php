<x-layouts.app :seo="$seo" :faq-schema="$faqSchema ?? null">
    <x-page-hero
        eyebrow="FAQ"
        title="Questions, answered plainly."
        copy="Everything here comes from how Recursive Frog actually works — including the parts that are still being finalised."
        :crumbs="['Home' => route('home'), 'FAQ' => null]" />

    <section class="py-16 md:py-24">
        <div class="shell">
            <div class="grid gap-12 lg:grid-cols-[0.75fr_1.25fr] lg:gap-20">
                <div class="lg:sticky lg:top-28 lg:self-start">
                    <x-eyebrow>Still deciding?</x-eyebrow>
                    <h2 class="mt-5 text-2xl leading-[1.1] sm:text-3xl">Ask us directly.</h2>
                    <p class="mt-4 text-[0.9375rem] leading-relaxed text-mist-400">
                        Questions are answered within one business day. If you are not sure which tier you
                        need, that is a perfectly good place to start.
                    </p>

                    <x-btn :href="route('contact')" track="faq_cta" class="mt-8 w-full sm:w-auto">Find Your Tier</x-btn>
                </div>

                <div>
                    @if ($faqs->isEmpty())
                        <p class="text-mist-400">Questions are being prepared. Please get in touch directly and we will answer.</p>
                    @else
                        <x-faq-accordion :faqs="$faqs" />
                    @endif
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
