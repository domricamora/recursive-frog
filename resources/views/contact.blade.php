<x-layouts.app :seo="$seo">
    <x-page-hero
        eyebrow="Find your tier"
        title="Let’s find the tier that fits your clinic."
        copy="Tell us a little about your clinic and how you currently handle bookings, inquiries and administration."
        :crumbs="['Home' => route('home'), 'Contact' => null]" />

    <section class="py-16 md:py-24">
        <div class="shell">
            <div class="grid gap-12 lg:grid-cols-[1.15fr_0.85fr] lg:gap-16">

                {{-- The form (plan.md #22). Server-side validation is always
                     authoritative; the client only assists (plan.md #35). --}}
                <div>
                    <form method="POST" action="{{ route('contact.store') }}" novalidate
                          data-track-form="find-your-tier" class="panel p-6 sm:p-8">
                        @csrf

                        {{-- Attribution + honeypot (plan.md #23, #35) --}}
                        @foreach (['source_url', 'utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content'] as $hidden)
                            <input type="hidden" name="{{ $hidden }}" value="{{ old($hidden) }}">
                        @endforeach

                        <div class="hidden" aria-hidden="true">
                            <label for="company-fax">Company fax</label>
                            <input type="text" id="company-fax" name="company_fax" tabindex="-1" autocomplete="off" value="{{ old('company_fax') }}">
                        </div>

                        @if ($errors->any())
                            <div role="alert" class="mb-8 rounded-xl border border-rose-ish/40 bg-rose-ish/10 p-5">
                                <p class="font-display font-semibold text-rose-ish">Please check the form</p>
                                <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-mist-300">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <fieldset>
                            <legend class="label mb-4">About you</legend>

                            <div class="grid gap-6 sm:grid-cols-2">
                                <x-form-field name="first_name" label="First name" :value="old('first_name')" placeholder="Juan" />
                                <x-form-field name="last_name" label="Last name" :value="old('last_name')" placeholder="Dela Cruz" />
                                <x-form-field name="clinic_name" label="Clinic name" :value="old('clinic_name')" placeholder="Your clinic" :span="true" />
                                <x-form-field name="email" label="Email" type="email" :value="old('email')" placeholder="you@clinic.ph" />
                                <x-form-field name="phone" label="Phone" type="tel" :value="old('phone')" placeholder="+63 917 000 0000" />
                                <x-form-field name="city" label="City" :value="old('city', 'Cebu City')" />
                                <x-form-field name="website" label="Current website" :value="old('website')"
                                              placeholder="https://… or “None”"
                                              hint="If your clinic has no website yet, type “None”." />
                            </div>

                            <div class="mt-6">
                                <span class="label">Preferred contact method <span class="text-jade-400">*</span></span>
                                <div class="flex flex-wrap gap-2" role="radiogroup" aria-label="Preferred contact method">
                                    @foreach ($preferredContacts as $value => $label)
                                        <label class="cursor-pointer">
                                            <input type="radio" name="preferred_contact" value="{{ $value }}" class="peer sr-only"
                                                   @checked(old('preferred_contact', 'email') === $value)>
                                            <span class="inline-block rounded-lg border border-navy-700 px-4 py-2.5 text-sm text-mist-300 transition-colors hover:border-navy-600 peer-checked:border-jade-500/60 peer-checked:bg-jade-500/10 peer-checked:text-jade-200 peer-focus-visible:outline peer-focus-visible:outline-2 peer-focus-visible:outline-offset-2 peer-focus-visible:outline-jade-400">
                                                {{ $label }}
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                                @error('preferred_contact') <span class="error">{{ $message }}</span> @enderror
                            </div>
                        </fieldset>

                        <fieldset class="mt-10">
                            <legend class="label mb-4">Your clinic today</legend>

                            <div class="grid gap-6 sm:grid-cols-2">
                                <x-form-field name="challenge" label="Biggest digital challenge" type="textarea" :span="true"
                                              :value="old('challenge')" :rows="3"
                                              placeholder="What is the hardest part of running the clinic online right now?" />

                                <x-form-field name="booking_process" label="How you manage bookings" type="textarea" :span="true"
                                              :value="old('booking_process')" :rows="3"
                                              placeholder="Paper diary, personal calendar, Messenger thread…?" />

                                <x-form-field name="inquiry_process" label="How inquiries reach you" type="textarea" :span="true"
                                              :value="old('inquiry_process')" :rows="3"
                                              placeholder="Which channels, and who checks them?" />

                                <x-form-field name="software_usage" label="Clinic management software" type="textarea" :span="true"
                                              :value="old('software_usage')" :rows="3" :required="false"
                                              placeholder="If you use any, which one? If not, type “None”." />
                            </div>
                        </fieldset>

                        <fieldset class="mt-10">
                            <legend class="label mb-4">What you are interested in</legend>

                            @php
                                $tierOptions = [
                                    \App\Models\Lead::TIER_NOT_SURE => ['Not sure', 'Help me work out the right starting point.'],
                                ];
                                foreach ($tiers as $tier) {
                                    $tierOptions[$tier->slug] = [$tier->name, $tier->includes];
                                }
                            @endphp

                            <div class="grid gap-3 sm:grid-cols-2">
                                @foreach ($tierOptions as $value => [$label, $note])
                                    <label class="cursor-pointer">
                                        <input type="radio" name="tier_interest" value="{{ $value }}" class="peer sr-only"
                                               data-track-tier="contact"
                                               @checked(old('tier_interest', \App\Models\Lead::TIER_NOT_SURE) === $value)>
                                        <span class="flex h-full flex-col rounded-xl border border-navy-700 p-5 transition-colors hover:border-navy-600 peer-checked:border-jade-500/60 peer-checked:bg-jade-500/10 peer-focus-visible:outline peer-focus-visible:outline-2 peer-focus-visible:outline-offset-2 peer-focus-visible:outline-jade-400">
                                            <span class="font-display font-semibold text-mist-50">{{ $label }}</span>
                                            <span class="mt-1 text-sm text-mist-400">{{ $note }}</span>
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                            @error('tier_interest') <span class="error">{{ $message }}</span> @enderror
                        </fieldset>

                        <fieldset class="mt-10">
                            <legend class="label mb-4">Anything else</legend>

                            <x-form-field name="message" label="Tell us anything else we should know" type="textarea"
                                          :value="old('message')" :rows="5" :required="false" :span="true" />

                            <label class="mt-6 flex cursor-pointer items-start gap-3">
                                <input type="checkbox" name="consent" value="1" class="mt-1 h-4 w-4 shrink-0 accent-[#96d410]"
                                       @checked(old('consent')) required
                                       @if ($errors->has('consent')) aria-invalid="true" aria-describedby="consent-error" @endif>
                                <span class="text-sm leading-relaxed text-mist-300">
                                    I agree to be contacted regarding my inquiry.
                                    <span class="text-jade-400">*</span>
                                </span>
                            </label>
                            @error('consent') <span class="error" id="consent-error">{{ $message }}</span> @enderror

                            <p class="mt-4 text-xs leading-relaxed text-mist-500">
                                Please do not include medical histories or patient records. We only ask for the
                                business information we need to reply to you.
                            </p>

                            <div class="mt-8 flex flex-col gap-4 sm:flex-row sm:items-center">
                                <x-btn type="submit" track="contact_submit" class="w-full sm:w-auto">
                                    Find My Tier
                                    <svg class="h-4 w-4" viewBox="0 0 16 16" fill="none" aria-hidden="true">
                                        <path d="M3 8h9m0 0-3.2-3.2M12 8l-3.2 3.2" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </x-btn>
                                <p class="font-mono text-[0.6875rem] uppercase tracking-[0.12em] text-mist-500">
                                    Replies within one business day
                                </p>
                            </div>
                        </fieldset>
                    </form>
                </div>

                {{-- Reassurance rail --}}
                <aside class="space-y-6">
                    <div class="panel p-7">
                        <p class="eyebrow">What happens next</p>
                        <ol class="mt-5 space-y-5">
                            @foreach ([
                                ['We read your answers', 'Your inquiry lands with the team, not in a shared inbox nobody checks.'],
                                ['We reply within one business day', 'With an honest view on which tier fits — including if that is “not yet”.'],
                                ['We scope it clearly', 'If it is a fit, you get a clear scope before anything is built.'],
                            ] as $i => [$title, $body])
                                <li class="flex gap-4">
                                    <span class="mt-0.5 font-mono text-[0.6875rem] text-jade-400 tnum">0{{ $i + 1 }}</span>
                                    <div>
                                        <h2 class="font-display font-semibold text-mist-50">{{ $title }}</h2>
                                        <p class="mt-1.5 text-[0.875rem] leading-relaxed text-mist-400">{{ $body }}</p>
                                    </div>
                                </li>
                            @endforeach
                        </ol>
                    </div>

                    <div class="panel p-7">
                        <p class="eyebrow">Or reach us directly</p>
                        <ul class="mt-4 space-y-3 text-[0.9375rem]">
                            <li><a href="mailto:{{ $site['email'] }}" class="break-all text-mist-200 hover:text-jade-300">{{ $site['email'] }}</a></li>
                            <li><a href="tel:{{ preg_replace('/[^\d+]/', '', $site['phone']) }}" class="text-mist-200 hover:text-jade-300">{{ $site['phone'] }}</a></li>
                        </ul>
                    </div>

                    <div class="panel p-7">
                        <p class="eyebrow">Privacy</p>
                        <p class="mt-4 text-[0.875rem] leading-relaxed text-mist-400">
                            We store only what we need to reply, keep it behind role-based access, and never
                            ask for patient or medical details through this form.
                        </p>
                    </div>
                </aside>
            </div>
        </div>
    </section>

    {{-- A calm closing beat: the room the conversation happens in. --}}
    <x-film-panel clip="clinic" height="min-h-[54svh]" scrim="film-scrim-soft">
        <div class="shell-flush relative flex flex-1 flex-col justify-end py-20 md:py-24">
            <div class="max-w-2xl">
                <x-eyebrow>No pressure</x-eyebrow>
                <p class="display display-3 mt-6">
                    If you are not sure yet, say that. We will tell you honestly what
                    you actually need first.
                </p>
            </div>
        </div>
    </x-film-panel>
</x-layouts.app>

