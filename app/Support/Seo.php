<?php

namespace App\Support;

use App\Models\ServiceTier;
use Illuminate\Support\Str;

/** Builds the metadata bag consumed by the public layout (plan.md #29). */
class Seo
{
    /** @var array<string, mixed> */
    protected static array $meta = [];

    public static function make(
        ?string $title = null,
        ?string $description = null,
        ?string $image = null,
        ?string $type = 'website',
    ): array {
        $company = Site::name();

        return [
            'title' => filled($title) ? $title.' — '.$company : Site::get('seo_title'),
            'raw_title' => filled($title) ? $title : null,
            'description' => filled($description) ? $description : Site::get('seo_description'),
            'image' => $image ?: Site::get('seo_og_image'),
            'type' => $type,
            'url' => url()->current(),
            'site_name' => $company,
        ];
    }

    /**
     * Organisation + website schema used across the public site (plan.md #29).
     *
     * @return array<string, mixed>
     */
    public static function organisationSchema(): array
    {
        $schema = array_filter([
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            '@id' => url('/').'#organization',
            'name' => Site::name(),
            'url' => url('/'),
            'email' => Site::email(),
            'telephone' => Site::phone(),
            'description' => Site::get('seo_description'),
            'logo' => asset('img/recursivefrog-logo.png'),
            'address' => [
                '@type' => 'PostalAddress',
                'addressLocality' => Site::get('city'),
                'addressCountry' => 'PH',
            ],
            'areaServed' => [
                '@type' => 'City',
                'name' => 'Cebu City',
            ],
            'sameAs' => array_values(Site::socialLinks()),
            'knowsAbout' => [
                'Website design and development',
                'Custom business software',
                'SaaS development',
                'Workflow automation',
                'E-commerce',
                'Booking platforms',
            ],
        ]);

        return $schema;
    }

    /** @return array<string, mixed> */
    public static function websiteSchema(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            '@id' => url('/').'#website',
            'url' => url('/'),
            'name' => Site::name(),
            'publisher' => ['@id' => url('/').'#organization'],
            'inLanguage' => 'en-PH',
        ];
    }

    /** @return array<string, mixed> */
    public static function serviceSchema(ServiceTier $tier): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Service',
            'name' => $tier->name,
            'description' => $tier->short_description ?: $tier->summary,
            'serviceType' => $tier->includes,
            'url' => route('services.show', $tier),
            'provider' => ['@id' => url('/').'#organization'],
            'areaServed' => [
                '@type' => 'City',
                'name' => 'Cebu City',
            ],
        ];
    }

    /** @param  iterable<int, \App\Models\Faq>  $faqs */
    public static function faqSchema(iterable $faqs): array
    {
        $items = collect($faqs)->map(fn ($faq) => [
            '@type' => 'Question',
            'name' => $faq->question,
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => Str::of(strip_tags((string) $faq->answer))->squish()->toString(),
            ],
        ])->all();

        return array_filter([
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $items,
        ]);
    }

    /** @return array<string, mixed> */
    public static function breadcrumbSchema(array $items): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => collect($items)
                ->values()
                ->map(fn (array $item, int $index) => [
                    '@type' => 'ListItem',
                    'position' => $index + 1,
                    'name' => $item['label'],
                    'item' => $item['url'] ?? url('/'),
                ])->all(),
        ];
    }
}
