<?php

namespace App\Http\Controllers;

use App\Models\ServiceTier;
use App\Support\Seo;
use Illuminate\View\View;

/** Service tiers overview and detail pages (plan.md #16, #17). */
class ServiceTierController extends Controller
{
    public function index(): View
    {
        return $this->publicView('services.index', [
            'title' => 'Services',
            'description' => 'Choose the system your clinic needs today. Start with the foundation, add software as your operation grows, and introduce automation when it makes sense.',
        ], [
            'tiers' => ServiceTier::query()
                ->active()
                ->ordered()
                ->with(['features' => fn ($q) => $q->where('status', ServiceTier::STATUS_CONFIRMED)])
                ->get(),
        ]);
    }

    public function show(ServiceTier $tier): View
    {
        abort_unless($tier->is_active, 404);

        $tier->load('features');

        return $this->publicView('services.show', [
            'title' => $tier->name,
            'description' => $tier->short_description ?: $tier->summary,
        ], [
            'tier' => $tier,
            'publishedFeatures' => $tier->features->where('status', ServiceTier::STATUS_CONFIRMED),
            'otherTiers' => ServiceTier::query()->active()->ordered()->whereKeyNot($tier->getKey())->get(),
            'breadcrumbSchema' => Seo::breadcrumbSchema([
                ['label' => 'Home', 'url' => route('home')],
                ['label' => 'Services', 'url' => route('services.index')],
                ['label' => $tier->name, 'url' => route('services.show', $tier)],
            ]),
        ]);
    }
}
