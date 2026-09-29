<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Project;
use App\Models\ServiceTier;
use App\Models\TeamMember;
use App\Support\Seo;
use Illuminate\View\View;

/** Homepage — the full Build / Automate / Scale narrative (plan.md #6–#15). */
class HomeController extends Controller
{
    public function __invoke(): View
    {
        return $this->publicView('home', [
            'title' => null, // uses the configured default title
            'description' => 'Build a better booking experience, automate repetitive admin work, and give your aesthetics clinic the digital systems it needs to grow.',
        ], [
            'tiers' => ServiceTier::query()
                ->active()
                ->ordered()
                ->with(['features' => fn ($q) => $q->where('status', ServiceTier::STATUS_CONFIRMED)])
                ->get(),
            'featuredProjects' => Project::query()->published()->featured()->ordered()->with('features')->take(6)->get(),
            'team' => TeamMember::query()->published()->notSpecialist()->ordered()->get(),
            'faqs' => Faq::query()->published()->ordered()->take(6)->get(),
            'commitments' => config('site.commitments'),
            'organisationSchema' => Seo::organisationSchema(),
        ]);
    }
}
