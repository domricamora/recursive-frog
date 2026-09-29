<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

/**
 * Editable site settings (plan.md #34). Blank values fall back to .env so the
 * app works before anyone opens the admin.
 */
class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->settings() as $order => $setting) {
            SiteSetting::updateOrCreate(
                ['key' => $setting['key']],
                [...$setting, 'display_order' => $order + 1],
            );
        }
    }

    /** @return array<int, array<string, string>> */
    protected function settings(): array
    {
        return [
            [
                'key' => 'company_name', 'label' => 'Company name', 'group' => 'contact', 'type' => 'text',
                'hint' => 'Shown in the header, footer and page titles.',
                'value' => (string) config('site.company_name'),
            ],
            [
                'key' => 'tagline', 'label' => 'Tagline', 'group' => 'contact', 'type' => 'text',
                'hint' => 'The core message. Build. Automate. Scale.',
                'value' => (string) config('site.tagline'),
            ],
            [
                'key' => 'email', 'label' => 'Public email', 'group' => 'contact', 'type' => 'email',
                'hint' => 'Shown in the footer and on the contact page.',
                'value' => (string) config('site.email'),
            ],
            [
                'key' => 'phone', 'label' => 'Phone', 'group' => 'contact', 'type' => 'tel',
                'hint' => 'Include the country code, e.g. +63 917 000 0000.',
                'value' => (string) config('site.phone'),
            ],
            [
                'key' => 'city', 'label' => 'City', 'group' => 'contact', 'type' => 'text',
                'hint' => 'Displayed next to the contact details.',
                'value' => (string) config('site.city'),
            ],
            [
                'key' => 'address', 'label' => 'Address', 'group' => 'contact', 'type' => 'textarea',
                'hint' => 'Full address as it should appear publicly.',
                'value' => (string) config('site.address'),
            ],
            [
                'key' => 'social_facebook', 'label' => 'Facebook URL', 'group' => 'social', 'type' => 'url',
                'hint' => 'Leave blank to hide the link.', 'value' => '',
            ],
            [
                'key' => 'social_linkedin', 'label' => 'LinkedIn URL', 'group' => 'social', 'type' => 'url',
                'hint' => 'Leave blank to hide the link.', 'value' => '',
            ],
            [
                'key' => 'social_instagram', 'label' => 'Instagram URL', 'group' => 'social', 'type' => 'url',
                'hint' => 'Leave blank to hide the link.', 'value' => '',
            ],
            [
                'key' => 'seo_title', 'label' => 'Default page title', 'group' => 'seo', 'type' => 'text',
                'hint' => 'Used on the home page and as a fallback everywhere else.',
                'value' => (string) config('site.seo.title'),
            ],
            [
                'key' => 'seo_description', 'label' => 'Meta description', 'group' => 'seo', 'type' => 'textarea',
                'hint' => 'Aim for 140–160 characters. Used for search results and link previews.',
                'value' => (string) config('site.seo.description'),
            ],
            [
                'key' => 'seo_og_image', 'label' => 'Social share image', 'group' => 'seo', 'type' => 'text',
                'hint' => 'Path relative to /public, e.g. img/og-cover.png.',
                'value' => (string) config('site.seo.og_image'),
            ],
        ];
    }
}
