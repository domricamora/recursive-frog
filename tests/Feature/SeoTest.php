<?php

namespace Tests\Feature;

use App\Models\Faq;
use App\Models\Project;
use App\Models\ServiceTier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_sitemap_lists_public_routes(): void
    {
        ServiceTier::create(['name' => 'Recursive 3', 'slug' => 'recursive-3']);
        Project::create(['name' => 'Cover & Keys', 'slug' => 'cover-and-keys', 'summary' => 'x']);

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml; charset=UTF-8')
            ->assertSee('<urlset', false)
            ->assertSee(url('/'), false)
            ->assertSee(url('/services/recursive-3'), false)
            ->assertSee(url('/work/cover-and-keys'), false);
    }

    public function test_the_sitemap_excludes_unpublished_content(): void
    {
        Project::create(['name' => 'Hidden', 'slug' => 'hidden', 'summary' => 'x', 'published' => false]);

        $this->get('/sitemap.xml')->assertOk()->assertDontSee('/work/hidden', false);
    }

    public function test_robots_points_at_the_sitemap_and_hides_the_admin(): void
    {
        $this->get('/robots.txt')
            ->assertOk()
            ->assertSee('Disallow: /admin', false)
            ->assertSee('Sitemap: '.route('sitemap'), false);
    }

    public function test_pages_carry_canonical_open_graph_and_description_tags(): void
    {
        $this->get('/about')
            ->assertOk()
            ->assertSee('<link rel="canonical"', false)
            ->assertSee('property="og:title"', false)
            ->assertSee('property="og:image"', false)
            ->assertSee('name="description"', false);
    }

    public function test_the_homepage_emits_organisation_schema(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('"@type":"Organization"', false);
    }

    public function test_tier_pages_emit_breadcrumb_schema(): void
    {
        ServiceTier::create(['name' => 'Recursive 3', 'slug' => 'recursive-3']);

        $this->get('/services/recursive-3')
            ->assertOk()
            ->assertSee('BreadcrumbList', false);
    }

    public function test_admin_sends_are_not_indexable(): void
    {
        $this->get('/admin/login')->assertOk()->assertSee('noindex', false);
    }
}
