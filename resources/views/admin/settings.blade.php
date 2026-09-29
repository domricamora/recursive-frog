<x-layouts.admin title="Settings">
    @section('content')
        <h1 class="font-display text-2xl font-semibold">Settings</h1>
        <p class="mt-1 max-w-2xl text-sm text-mist-500">
            These values override the matching <span class="font-mono text-xs">.env</span> entries at runtime.
            Leave a field blank to fall back to the environment value.
        </p>

        <form method="POST" action="{{ route('admin.settings.update') }}" class="mt-8 max-w-3xl space-y-6">
            @csrf
            @method('PUT')

            @foreach ($settings->groupBy('group') as $group => $groupSettings)
                <section class="panel p-6">
                    <h2 class="font-display text-lg font-semibold capitalize">{{ $group }}</h2>

                    <div class="mt-5 space-y-5">
                        @foreach ($groupSettings as $setting)
                            <div>
                                <label class="label" for="setting-{{ $setting->key }}">{{ $setting->label }}</label>

                                @if ($setting->type === 'textarea')
                                    <textarea class="field min-h-24 resize-y" id="setting-{{ $setting->key }}"
                                              name="settings[{{ $setting->key }}]"
                                              placeholder="{{ config('site.'.(str_starts_with($setting->key, 'seo_') ? 'seo.'.substr($setting->key, 4) : $setting->key)) }}"
                                    >{{ old('settings.'.$setting->key, $setting->value) }}</textarea>
                                @else
                                    <input class="field"
                                           type="{{ in_array($setting->type, ['url', 'email', 'tel']) ? $setting->type : 'text' }}"
                                           id="setting-{{ $setting->key }}"
                                           name="settings[{{ $setting->key }}]"
                                           value="{{ old('settings.'.$setting->key, $setting->value) }}">
                                @endif

                                @if ($setting->hint)
                                    <p class="mt-1.5 text-xs text-mist-500">{{ $setting->hint }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </section>
            @endforeach

            <div class="panel flex flex-wrap items-center gap-3 p-6">
                <button type="submit" class="btn btn-primary">Save settings</button>
                <a href="{{ route('admin.audit-log') }}" class="btn btn-ghost">View audit log</a>
            </div>
        </form>

        <section class="panel mt-6 max-w-3xl p-6">
            <h2 class="font-display text-lg font-semibold">Environment configuration</h2>
            <p class="mt-1 text-sm text-mist-500">Secrets and integration credentials live in <span class="font-mono text-xs">.env</span>, never in the database.</p>

            <ul class="mt-5 space-y-2 text-sm">
                @foreach ([
                    'MAIL_LEADS_TO' => 'Where new-inquiry notifications are sent.',
                    'HUBSPOT_ACCESS_TOKEN' => 'Enables the optional CRM contact sync.',
                    'GA4_MEASUREMENT_ID' => 'Enables GA4 events.',
                    'GTM_CONTAINER_ID' => 'Enables Google Tag Manager.',
                    'APP_URL' => 'Used for canonical URLs, sitemap and emails.',
                ] as $key => $note)
                    <li class="flex flex-wrap items-baseline gap-x-3 border-b border-navy-800 pb-2 last:border-0">
                        <span class="font-mono text-xs text-jade-400/80">{{ $key }}</span>
                        <span class="text-mist-400">{{ $note }}</span>
                    </li>
                @endforeach
            </ul>

            <ul class="mt-5 flex flex-wrap gap-2">
                @foreach ($integrationStatus as $name => $enabled)
                    <li @class(['chip', 'chip-accent' => $enabled])>{{ $name }} · {{ $enabled ? 'on' : 'off' }}</li>
                @endforeach
            </ul>
        </section>
    @endsection
</x-layouts.admin>
