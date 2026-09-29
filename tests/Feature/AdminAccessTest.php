<?php

namespace Tests\Feature;

use App\Models\ServiceTier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        ServiceTier::create(['name' => 'Recursive 3', 'slug' => 'recursive-3', 'model' => 'Basic model']);
    }

    public function test_guests_are_redirected_to_the_login_page(): void
    {
        $this->get('/admin')->assertRedirect(route('admin.login'));
    }

    public function test_an_admin_can_open_the_dashboard(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $this->actingAs($admin)->get('/admin')->assertOk()->assertSee('Dashboard');
    }

    public function test_a_viewer_cannot_manage_content(): void
    {
        $viewer = User::factory()->create(['role' => User::ROLE_VIEWER]);

        $this->actingAs($viewer)->get('/admin')->assertOk();          // may read
        $this->actingAs($viewer)->get('/admin/services')->assertForbidden();
    }

    public function test_an_editor_can_manage_content_but_not_settings(): void
    {
        $editor = User::factory()->create(['role' => User::ROLE_EDITOR]);

        $this->actingAs($editor)->get('/admin/services')->assertOk();
        $this->actingAs($editor)->get('/admin/settings')->assertForbidden();
    }

    public function test_admins_can_open_settings_and_the_audit_log(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $this->actingAs($admin)->get('/admin/settings')->assertOk();
        $this->actingAs($admin)->get('/admin/audit-log')->assertOk();
    }

    public function test_signing_in_with_bad_credentials_fails(): void
    {
        User::factory()->create(['email' => 'wrong@example.com', 'password' => 'secret-password']);

        $this->from('/admin/login')
            ->post('/admin/login', ['email' => 'wrong@example.com', 'password' => 'nope'])
            ->assertRedirect('/admin/login')
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_a_valid_sign_in_redirects_to_the_dashboard(): void
    {
        $user = User::factory()->create(['password' => 'secret-password']);

        $this->post('/admin/login', ['email' => $user->email, 'password' => 'secret-password'])
            ->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($user);
    }
}
