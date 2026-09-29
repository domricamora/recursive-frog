<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Recursive Frog site defaults
    |--------------------------------------------------------------------------
    |
    | Every value here can be overridden at runtime by a row in the
    | `site_settings` table (Admin > Settings), which takes precedence.
    | See App\Support\Site for the resolution helper.
    |
    */

    'company_name' => env('SITE_COMPANY_NAME', 'Recursive Frog'),

    'tagline' => env('SITE_TAGLINE', 'Build. Automate. Scale.'),

    // Note: single quotes do not interpret \u{...}, so the typographic
    // apostrophe is written literally here.
    'positioning' => 'Digital systems for Cebu’s aesthetics clinics.',

    'email' => env('SITE_EMAIL', 'hello@recursivefrog.ph'),

    'phone' => env('SITE_PHONE', '+63 917 000 0000'),

    'city' => env('SITE_CITY', 'Cebu City, Philippines'),

    'address' => env('SITE_ADDRESS', 'Cebu City, Philippines'),

    'map_embed' => env('SITE_MAP_EMBED', ''),

    'social' => [
        'facebook' => '',
        'linkedin' => '',
        'instagram' => '',
        'github' => '',
    ],

    /*
    |--------------------------------------------------------------------------
    | SEO defaults (editable under Admin > Settings)
    |--------------------------------------------------------------------------
    */

    'seo' => [
        'title' => 'Recursive Frog — Digital Systems for Cebu Aesthetics Clinics',
        'description' => 'Build a better booking experience, automate repetitive admin work, and give your aesthetics clinic the digital systems it needs to grow.',
        'og_image' => 'img/og-cover.png',
        'twitter_handle' => '',
    ],

    /*
    |--------------------------------------------------------------------------
    | Service commitments shown in the trust section (plan.md #13)
    |--------------------------------------------------------------------------
    */

    'commitments' => [
        [
            'title' => 'Weekly progress',
            'body' => 'A short written update and a link to try the latest version.',
        ],
        [
            'title' => 'Fast replies',
            'body' => 'Questions answered within one business day.',
        ],
        [
            'title' => 'Tested before done',
            'body' => 'Checked on phones, tablets and computers first.',
        ],
        [
            'title' => 'You own it',
            'body' => 'Website, code and data belong to your clinic once paid in full.',
        ],
        [
            'title' => 'Patient data protected',
            'body' => 'Access levels per role, activity logs and consent-first processes.',
        ],
        [
            'title' => 'AI does admin only',
            'body' => 'AI does not diagnose or recommend treatments.',
        ],
    ],

];
