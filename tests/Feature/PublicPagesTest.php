<?php

namespace Tests\Feature;

use App\Models\Faq;
use App\Models\Project;
use App\Models\ServiceFeature;
use App\Models\ServiceTier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_homepage_carries_the_core_message_and_ctas(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Build.', false)
            ->assertSee('Automate.', false)
            ->assertSee('Scale.', false)
            ->assertSee('Digital systems for Cebu', false)
            ->assertSee(route('contact'), false)
            ->assertSee(route('work.index'), false);
    }

    public function test_the_homepage_links_to_every_active_tier(): void
    {
        ServiceTier::create(['name' => 'Recursive 3', 'slug' => 'recursive-3', 'model' => 'Basic model']);
        ServiceTier::create(['name' => 'Recursive 5', 'slug' => 'recursive-5', 'model' => 'Pro model']);

        $this->get('/')
            ->assertOk()
            ->assertSee(route('services.show', 'recursive-3'), false)
            ->assertSee(route('services.show', 'recursive-5'), false);
    }

    public function test_inactive_tiers_are_not_listed(): void
    {
        ServiceTier::create([
            'name' => 'Recursive 7', 'slug' => 'recursive-7', 'is_active' => false,
        ]);

        $this->get('/services')->assertOk()->assertDontSee(route('services.show', 'recursive-7'), false);
    }

    public function test_a_tier_page_hides_draft_features(): void
    {
        $tier = ServiceTier::create([
            'name' => 'Recursive 3',
            'slug' => 'recursive-3',
            'model' => 'Basic model',
            'includes' => 'Website',
        ]);

        ServiceFeature::create([
            'service_tier_id' => $tier->id,
            'name' => 'Confirmed inclusion',
            'status' => ServiceTier::STATUS_CONFIRMED,
        ]);
        ServiceFeature::create([
            'service_tier_id' => $tier->id,
            'name' => 'Still being finalised',
            'status' => ServiceTier::STATUS_DRAFT,
        ]);

        $this->get(route('services.show', $tier))
            ->assertOk()
            ->assertSee('Confirmed inclusion')
            ->assertDontSee('Still being finalised');
    }

    public function test_promoting_a_draft_feature_publishes_it(): void
    {
        $tier = ServiceTier::create(['name' => 'Recursive 3', 'slug' => 'recursive-3']);
        $feature = ServiceFeature::create([
            'service_tier_id' => $tier->id,
            'name' => 'Treatment pages',
            'status' => ServiceTier::STATUS_DRAFT,
        ]);

        $this->get(route('services.show', $tier))->assertDontSee('Treatment pages');

        $feature->update(['status' => ServiceTier::STATUS_CONFIRMED]);

        $this->get(route('services.show', $tier))->assertSee('Treatment pages');
    }

    public function test_every_static_page_renders(): void
    {
        foreach (['/how-it-works', '/about', '/faq', '/work'] as $uri) {
            $this->get($uri)->assertOk();
        }
    }

    public function test_unpublished_projects_are_not_reachable(): void
    {
        Project::create([
            'name' => 'Hidden',
            'slug' => 'hidden',
            'summary' => 'Not ready yet.',
            'published' => false,
        ]);

        $this->get('/work')->assertOk()->assertDontSee(route('work.show', 'hidden'), false);
        $this->get('/work/hidden')->assertNotFound();
    }

    public function test_the_thank_you_page_thanks_the_visitor(): void
    {
        $this->get('/contact/thank-you')
            ->assertOk()
            ->assertSee('Thanks.', false)
            ->assertSee('Explore Our Work', false);
    }

    public function test_the_faq_page_emits_question_schema(): void
    {
        Faq::create(['question' => 'Do we own the website?', 'answer' => 'Yes.']);

        $this->get('/faq')
            ->assertOk()
            ->assertSee('FAQPage', false)
            ->assertSee('Do we own the website?');
    }

    public function test_responsible_ai_language_is_present_on_the_site(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('AI does admin only', false)
            ->assertSee('AI does not diagnose', false);
    }
}
