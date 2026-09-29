<?php

namespace Tests\Feature;

use App\Models\Lead;
use App\Models\Project;
use App\Models\ServiceFeature;
use App\Models\ServiceTier;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminContentTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    }

    public function test_an_editor_can_create_and_update_a_tier(): void
    {
        $editor = User::factory()->create(['role' => User::ROLE_EDITOR]);

        $this->actingAs($editor)
            ->post('/admin/services', [
                'name' => 'Recursive 9',
                'slug' => 'recursive-9',
                'model' => 'Test model',
                'display_order' => 9,
            ])
            ->assertRedirect();

        $tier = ServiceTier::where('slug', 'recursive-9')->firstOrFail();
        $this->assertFalse($tier->is_active, 'is_active is only on when the box is ticked.');

        $this->actingAs($editor)
            ->put('/admin/services/'.$tier->slug, [
                'name' => 'Recursive 9',
                'slug' => 'recursive-9',
                'model' => 'Updated model',
                'display_order' => 9,
                'is_active' => '1',
            ])
            ->assertSessionHasNoErrors();

        $this->assertTrue($tier->fresh()->is_active);
        $this->assertSame('Updated model', $tier->fresh()->model);
    }

    public function test_features_can_be_added_promoted_and_removed(): void
    {
        $tier = ServiceTier::create(['name' => 'Recursive 3', 'slug' => 'recursive-3']);

        $response = $this->actingAs($this->admin)
            ->post("/admin/services/{$tier->slug}/features", ['name' => 'Treatment pages', 'status' => 'draft']);

        $response->assertSessionHasNoErrors();
        $this->assertTrue(
            ServiceFeature::where('name', 'Treatment pages')->exists(),
            'Feature was not created (HTTP '.$response->getStatusCode().').'
        );

        $feature = ServiceFeature::firstOrFail();
        $this->assertSame('draft', $feature->status);

        $this->actingAs($this->admin)
            ->put("/admin/services/{$tier->slug}/features/{$feature->id}", [
                'name' => 'Treatment pages', 'status' => 'confirmed', 'display_order' => 1,
            ])
            ->assertSessionHasNoErrors();

        $this->assertSame('confirmed', $feature->fresh()->status);

        $this->actingAs($this->admin)
            ->delete("/admin/services/{$tier->slug}/features/{$feature->id}")
            ->assertSessionHasNoErrors();

        $this->assertDatabaseCount('service_features', 0);
    }

    public function test_a_feature_cannot_be_edited_through_the_wrong_tier(): void
    {
        $tier = ServiceTier::create(['name' => 'Recursive 3', 'slug' => 'recursive-3']);
        $other = ServiceTier::create(['name' => 'Recursive 5', 'slug' => 'recursive-5']);
        $feature = ServiceFeature::create(['service_tier_id' => $other->id, 'name' => 'X']);

        $this->actingAs($this->admin)
            ->put("/admin/services/{$tier->slug}/features/{$feature->id}", [
                'name' => 'X', 'status' => 'confirmed', 'display_order' => 1,
            ])
            ->assertNotFound();
    }

    public function test_projects_faqs_and_team_are_manageable_without_code_changes(): void
    {
        $this->actingAs($this->admin)
            ->post('/admin/projects', [
                'name' => 'New Project',
                'slug' => 'new-project',
                'summary' => 'A summary.',
                'technologies' => 'Laravel, React',
                'display_order' => 1,
                'published' => '1',
            ])
            ->assertSessionHasNoErrors();

        $project = Project::firstOrFail();
        $this->assertSame(['Laravel', 'React'], $project->technologies);
        $this->assertTrue($project->published);

        $this->actingAs($this->admin)
            ->post('/admin/faqs', ['question' => 'Q?', 'answer' => 'A.', 'display_order' => 1])
            ->assertSessionHasNoErrors();
        $this->assertDatabaseCount('faqs', 1);

        $this->actingAs($this->admin)
            ->post('/admin/team', ['role' => 'Tech', 'display_order' => 1])
            ->assertSessionHasNoErrors();
        $this->assertDatabaseCount('team_members', 1);
    }

    public function test_settings_override_the_environment_values(): void
    {
        $this->actingAs($this->admin)
            ->put('/admin/settings', [
                'settings' => [
                    'company_name' => 'Recursive Frog PH',
                    'tagline' => 'Build. Automate. Scale.',
                ],
            ])
            ->assertSessionHasNoErrors();

        $this->assertSame('Recursive Frog PH', SiteSetting::get('company_name'));
        $this->assertSame('Recursive Frog PH', \App\Support\Site::name());

        $this->get('/')->assertOk()->assertSee('Recursive Frog PH');
    }

    public function test_viewing_a_lead_is_audited(): void
    {
        $lead = Lead::create([
            'first_name' => 'Ana', 'last_name' => 'Reyes',
            'clinic_name' => 'Ana Clinic', 'email' => 'ana@clinic.ph',
            'phone' => '+63 917 000 0000',
        ]);

        $this->actingAs($this->admin)->get("/admin/leads/{$lead->id}")->assertOk();

        $this->assertDatabaseHas('audit_logs', [
            'action' => 'lead.viewed',
            'user_id' => $this->admin->id,
        ]);
    }

    public function test_leads_can_be_exported_as_csv(): void
    {
        Lead::create([
            'first_name' => 'Ana', 'last_name' => 'Reyes',
            'clinic_name' => 'Ana Clinic', 'email' => 'ana@clinic.ph',
            'phone' => '+63 917 000 0000',
        ]);

        $this->actingAs($this->admin)
            ->get('/admin/leads/export')
            ->assertOk()
            ->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }

    public function test_notes_and_status_updates_are_recorded(): void
    {
        $lead = Lead::create([
            'first_name' => 'Ana', 'last_name' => 'Reyes',
            'clinic_name' => 'Ana Clinic', 'email' => 'ana@clinic.ph',
            'phone' => '+63 917 000 0000',
        ]);

        $this->actingAs($this->admin)
            ->patch("/admin/leads/{$lead->id}", ['status' => 'qualified'])
            ->assertSessionHasNoErrors();
        $this->assertSame('qualified', $lead->fresh()->status);

        $this->actingAs($this->admin)
            ->post("/admin/leads/{$lead->id}/notes", ['body' => 'Called them.'])
            ->assertSessionHasNoErrors();
        $this->assertDatabaseHas('lead_notes', ['body' => 'Called them.']);
    }
}
