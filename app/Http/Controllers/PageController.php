<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Support\Seo;
use Illuminate\View\View;

/** Static marketing pages plus the FAQ index (plan.md #18, #20, #21). */
class PageController extends Controller
{
    public function howItWorks(): View
    {
        return $this->publicView('how-it-works', [
            'title' => 'How It Works',
            'description' => 'A clear process for building business websites, custom software and automation: discovery, planning, design, testing and launch.',
        ], [
            'breadcrumbSchema' => Seo::breadcrumbSchema([
                ['label' => 'Home', 'url' => route('home')],
                ['label' => 'How It Works', 'url' => route('how-it-works')],
            ]),
        ]);
    }

    public function about(): View
    {
        return $this->publicView('about', [
            'title' => 'About',
            'description' => 'Meet the Cebu team building practical websites, custom software and workflow automation for businesses across industries.',
        ], [
            'profiles' => [
                [
                    'name' => 'Nick Ricamora',
                    'role' => 'Technical specialist',
                    'focus' => 'Full-stack web developer, Cebu City',
                    'photo' => 'img/team/nick.png',
                    'alt' => 'Portrait of Nick Ricamora',
                    'bio' => 'Nick builds booking platforms, SaaS products, e-commerce stores and marketing sites. He works mainly with Laravel, React and PHP, and also builds with Shopify and WordPress.',
                    'highlights' => [
                        '20+ years building for the web',
                        'DOST Best Web Design award',
                        'Laravel, React and PHP, plus Shopify and WordPress',
                    ],
                    'portfolio' => 'https://onwardphilippines.com/portfolio',
                ],
                [
                    'name' => 'Ralph Dominic Aragon',
                    'role' => 'Operations specialist',
                    'focus' => 'Salon and aesthetics operations, HR and distribution',
                    'photo' => 'img/team/ralph.png',
                    'alt' => 'Portrait of Ralph Dominic Aragon',
                    'bio' => 'Ralph brings hands-on experience across salon and aesthetics operations, people management, e-commerce and virtual assistance.',
                    'highlights' => [
                        '7 years in salon and aesthetics operations and HR management',
                        '3 years in aesthetics distribution and e-commerce management (Shopee, TikTok and Lazada)',
                        '2 years in HVA: scribing, data entry and scheduling',
                        'Registered physical therapist in the Philippines',
                    ],
                    'portfolio' => null,
                ],
            ],
            'breadcrumbSchema' => Seo::breadcrumbSchema([
                ['label' => 'Home', 'url' => route('home')],
                ['label' => 'About', 'url' => route('about')],
            ]),
        ]);
    }

    public function faq(): View
    {
        $faqs = Faq::query()->published()->ordered()->get();

        return $this->publicView('faq', [
            'title' => 'FAQ',
            'description' => 'Answers about Recursive Frog services, business software, ownership, responsible AI, progress updates and support.',
        ], [
            'faqs' => $faqs,
            'faqSchema' => $faqs->isNotEmpty() ? Seo::faqSchema($faqs) : null,
        ]);
    }
}
