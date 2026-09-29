@props([
    'title' => 'Let’s find the tier that fits your clinic.',
    'copy' => 'Tell us how you handle bookings, inquiries and administration today. We will point you at the right starting point — even if that is “not sure yet”.',
    'track' => 'cta_band',
    'clip' => 'consult',
])

{{-- A full-bleed film with a cream plate laid over its lower right, the way a
     printed invitation sits on a photograph. --}}
<section class="film on-film relative">
    <img class="film-poster" src="{{ asset("media/poster/{$clip}.jpg") }}" alt="" width="1920" height="1080" loading="lazy" decoding="async">
    <video data-film class="film-media" autoplay muted loop playsinline preload="metadata" tabindex="-1">
        <source src="{{ asset("media/video/{$clip}.mp4") }}" type="video/mp4">
    </video>
    <span class="film-scrim" aria-hidden="true"></span>
    <span class="film-grain" aria-hidden="true"></span>

    <div class="shell-flush relative py-20 md:py-28 lg:py-36">
        <div class="flex justify-end">
            <div class="plate reveal w-full max-w-xl p-8 md:p-10">
                <x-eyebrow number="→">Next step</x-eyebrow>
                <h2 class="display display-3 mt-6">{{ $title }}</h2>
                <p class="mt-5 text-[1.0625rem] leading-relaxed text-ink-500">{{ $copy }}</p>

                <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                    <x-btn :href="route('contact')" variant="primary" :track="$track" class="w-full sm:w-auto">
                        Find Your Tier
                        <svg class="h-4 w-4" viewBox="0 0 16 16" fill="none" aria-hidden="true">
                            <path d="M3 8h9m0 0-3.2-3.2M12 8l-3.2 3.2" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </x-btn>
                    <x-btn :href="route('work.index')" variant="outline" :track="$track.'_work'" class="w-full sm:w-auto">See Our Work</x-btn>
                </div>
            </div>
        </div>
    </div>
</section>

