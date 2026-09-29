@props(['seo', 'filmHero' => false, 'organisationSchema' => null, 'faqSchema' => null, 'breadcrumbSchema' => null])
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $seo['title'] }}</title>
    <meta name="description" content="{{ $seo['description'] }}">
    <link rel="canonical" href="{{ $seo['url'] }}">
    <meta name="robots" content="index, follow, max-image-preview:large">
    <meta name="theme-color" content="#f5f2eb">

    {{-- Typefaces are self-hosted by the Vite font plugin (Archivo, Newsreader,
         Caveat, JetBrains Mono), so there is no third-party request to block
         first paint. Only the hero poster is preloaded. --}}
    <link rel="preload" as="image" href="{{ asset('media/poster/hero.jpg') }}" fetchpriority="high">

    {{-- Open Graph / Twitter (plan.md #29) --}}
    <meta property="og:site_name" content="{{ $seo['site_name'] }}">
    <meta property="og:title" content="{{ $seo['title'] }}">
    <meta property="og:description" content="{{ $seo['description'] }}">
    <meta property="og:url" content="{{ $seo['url'] }}">
    <meta property="og:type" content="{{ $seo['type'] }}">
    <meta property="og:locale" content="en_PH">
    <meta property="og:image" content="{{ asset($seo['image']) }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seo['title'] }}">
    <meta name="twitter:description" content="{{ $seo['description'] }}">
    <meta name="twitter:image" content="{{ asset($seo['image']) }}">

    <link rel="icon" href="{{ asset('img/recursivefrog-logo.png') }}" type="image/png">

    {{-- Structured data (plan.md #29) --}}
    @foreach (array_filter([$organisationSchema ?? null, $faqSchema ?? null, $breadcrumbSchema ?? null]) as $schema)
        <script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @endforeach

    @if (filled(config('services.analytics.gtm_container_id')))
        <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});
        var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;
        j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','{{ config('services.analytics.gtm_container_id') }}');</script>
    @endif

    @if (filled(config('services.analytics.ga4_measurement_id')))
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('services.analytics.ga4_measurement_id') }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '{{ config('services.analytics.ga4_measurement_id') }}', { anonymize_ip: true });
        </script>
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen overflow-x-clip antialiased">
    <a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:left-5 focus:top-5 focus:z-[100] focus:rounded-full focus:bg-ink-900 focus:px-5 focus:py-3 focus:text-sm focus:font-medium focus:text-bone-50">
        Skip to content
    </a>

    {{-- filmHero keeps the chrome white while it floats over the opening
         film; on interior pages the chrome sits on cream and starts in ink. --}}
    <x-nav :services="$navServices" :overlay="$filmHero" />

    <main id="main">
        {{ $slot }}
    </main>

    <x-footer :site="$site" />

    @stack('scripts')
</body>
</html>

