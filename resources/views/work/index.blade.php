<x-layouts.app :seo="$seo">
    <x-page-hero
        eyebrow="Our work"
        title="Real platforms. Real systems."
        copy="Explore examples of websites, booking platforms, SaaS products and automation systems built by Recursive Frog's technical team."
        :crumbs="['Home' => route('home'), 'Our Work' => null]" />

    <section class="py-16 md:py-24">
        <div class="shell">
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($projects as $project)
                    <div class="reveal"><x-project-card :project="$project" /></div>
                @endforeach
            </div>

            <p class="reveal mt-14 max-w-2xl text-[0.9375rem] leading-relaxed text-mist-500">
                Case studies are written from delivered work only. Where a figure has not been verified, it is
                not published here.
            </p>
        </div>
    </section>

    <x-cta-band
        title="Want something like this for your clinic?"
        copy="Tell us what you are trying to fix and we will tell you honestly which tier — if any — is the right fit." />
</x-layouts.app>
