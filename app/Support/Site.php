<?php

namespace App\Support;

use App\Models\SiteSetting;

/**
 * Resolves site settings, preferring admin-editable database values and
 * falling back to config/site.php (plan.md #34).
 */
class Site
{
    /** @var array<string, string>|null */
    protected static ?array $memo = null;

    /** @return array<string, string> */
    public static function all(): array
    {
        if (static::$memo !== null) {
            return static::$memo;
        }

        $defaults = collect(config('site'))
            ->flatMap(fn ($value, $key) => is_array($value) ? [] : [$key => (string) $value])
            ->merge([
                'seo_title' => (string) config('site.seo.title'),
                'seo_description' => (string) config('site.seo.description'),
                'seo_og_image' => (string) config('site.seo.og_image'),
                'social_facebook' => (string) (config('site.social.facebook') ?: ''),
                'social_linkedin' => (string) (config('site.social.linkedin') ?: ''),
                'social_instagram' => (string) (config('site.social.instagram') ?: ''),
            ])
            ->all();

        $stored = SiteSetting::map();

        return static::$memo = array_merge($defaults, array_filter(
            $stored,
            fn ($value) => $value !== null && $value !== ''
        ));
    }

    public static function get(string $key, ?string $default = null): string
    {
        return static::all()[$key] ?? (string) $default;
    }

    public static function email(): string
    {
        return static::get('email', 'hello@recursivefrog.ph');
    }

    public static function phone(): string
    {
        return static::get('phone');
    }

    public static function name(): string
    {
        return static::get('company_name', 'Recursive Frog');
    }

    /** Raw social links, skipping blank entries. @return array<string, string> */
    public static function socialLinks(): array
    {
        return array_filter([
            'Facebook' => static::get('social_facebook'),
            'LinkedIn' => static::get('social_linkedin'),
            'Instagram' => static::get('social_instagram'),
        ]);
    }

    public static function flush(): void
    {
        static::$memo = null;
    }
}
