<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\TeamMember;
use App\Support\Seo;
use Illuminate\View\View;

/** Static marketing pages plus the FAQ index (plan.md #18, #20, #21). */
class PageController extends Controller
{
    public function howItWorks(): View
    {
        return $this->publicView('how-it-works', [
            'title' => 'How It Works',
            'description' => 'From clinic problems to a working digital system: discovery, planning, design, development, testing and launch.',
        ], [
            'breadcrumbSchema' => Seo::breadcrumbSchema([
                ['label' => 'Home', 'url' => route('home')],
                ['label' => 'How It Works', 'url' => route('how-it-works')],
            ]),
        ]);
    }

    public function about(): View
    {
        $team = TeamMember::query()->published()->ordered()->get();

        return $this->publicView('about', [
            'title' => 'About',
            'description' => 'Recursive Frog builds digital systems for clinics that want more than a brochure website.',
        ], [
            'roles' => $team->where('is_specialist', false)->values(),
            'specialist' => $team->firstWhere('is_specialist', true),
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
            'description' => 'Answers about Recursive Frog services, ownership, clinic software, responsible AI, progress updates and support.',
        ], [
            'faqs' => $faqs,
            'faqSchema' => $faqs->isNotEmpty() ? Seo::faqSchema($faqs) : null,
        ]);
    }
}
