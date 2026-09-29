<?php

namespace Tests\Feature;

use App\Models\Lead;
use App\Models\ServiceFeature;
use App\Models\ServiceTier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    /** @return array<string, mixed> */
    protected function payload(array $overrides = []): array
    {
        return array_merge([
            'first_name' => 'Juan',
            'last_name' => 'Dela Cruz',
            'clinic_name' => 'Glow Cebu',
            'email' => 'juan@glowcebu.ph',
            'phone' => '+63 917 555 1234',
            'city' => 'Cebu City',
            'website' => 'https://glowcebu.ph',
            'preferred_contact' => 'messenger',
            'challenge' => 'Inquiries disappear into Messenger.',
            'booking_process' => 'Paper diary at the front desk.',
            'inquiry_process' => 'Messenger and Viber.',
            'software_usage' => 'None',
            'tier_interest' => 'not_sure',
            'message' => 'We would like a better booking experience.',
            'consent' => '1',
            'source_url' => 'https://recursivefrog.ph/',
            'utm_source' => 'facebook',
        ], $overrides);
    }

    public function test_the_form_page_renders_with_every_required_field(): void
    {
        $this->get('/contact')
            ->assertOk()
            ->assertSee('Find Your Tier', false)
            ->assertSee('name="first_name"', false)
            ->assertSee('name="clinic_name"', false)
            ->assertSee('name="preferred_contact"', false)
            ->assertSee('name="tier_interest"', false)
            ->assertSee('name="consent"', false);
    }

    public function test_a_valid_submission_stores_the_lead_and_redirects(): void
    {
        Mail::fake();

        $response = $this->from('/contact')->post('/contact', $this->payload());

        $response->assertRedirect(route('contact.thank-you'));
        $response->assertSessionHas('lead_reference');

        $this->assertDatabaseHas('leads', [
            'email' => 'juan@glowcebu.ph',
            'clinic_name' => 'Glow Cebu',
            'tier_interest' => 'not_sure',
            'tier_label' => 'Not sure yet',
            'consent' => true,
            'status' => Lead::STATUS_NEW,
            'utm_source' => 'facebook',
        ]);

        $lead = Lead::first();
        $this->assertNotNull($lead->consented_at);
        $this->assertNotNull($lead->source_url);
    }

    public function test_a_submission_notifies_the_team_and_the_prospect(): void
    {
        Mail::fake();

        $this->post('/contact', $this->payload());

        Mail::assertSent(
            \App\Mail\LeadInternalNotification::class,
            fn ($mail) => $mail->hasTo('hello@recursivefrog.ph')
        );

        Mail::assertSent(
            \App\Mail\LeadProspectConfirmation::class,
            fn ($mail) => $mail->hasTo('juan@glowcebu.ph')
    );
    }

    public function test_a_mail_failure_does_not_lose_the_lead(): void
    {
        Notification::fake();
        config(['mail.default' => 'smtp', 'mail.mailers.smtp.host' => '127.0.0.1', 'mail.mailers.smtp.port' => 1]);

        $this->post('/contact', $this->payload())->assertRedirect(route('contact.thank-you'));

        $this->assertDatabaseCount('leads', 1);
    }

    public function test_required_fields_are_enforced(): void
    {
        Mail::fake();

        $this->from('/contact')
            ->post('/contact', [])
            ->assertRedirect('/contact')
            ->assertSessionHasErrors([
                'first_name', 'last_name', 'clinic_name', 'email',
                'phone', 'city', 'website', 'preferred_contact',
                'challenge', 'booking_process', 'inquiry_process', 'tier_interest', 'consent',
            ]);

        $this->assertDatabaseCount('leads', 0);
    }

    public function test_consent_must_be_given(): void
    {
        $this->from('/contact')
            ->post('/contact', $this->payload(['consent' => null]))
            ->assertSessionHasErrors('consent');
    }

    public function test_the_honeypot_rejects_bots(): void
    {
        $this->from('/contact')
            ->post('/contact', $this->payload(['company_fax' => 'spam']))
            ->assertSessionHasErrors('company_fax');

        $this->assertDatabaseCount('leads', 0);
    }

    public function test_the_tier_label_resolves_against_live_records(): void
    {
        Mail::fake();

        ServiceTier::create(['name' => 'Recursive 5', 'slug' => 'recursive-5', 'model' => 'Pro model']);

        $this->post('/contact', $this->payload(['tier_interest' => 'recursive-5']));

        $this->assertDatabaseHas('leads', ['tier_interest' => 'recursive-5', 'tier_label' => 'Recursive 5']);
    }

    public function test_an_unknown_tier_is_rejected(): void
    {
        $this->from('/contact')
            ->post('/contact', $this->payload(['tier_interest' => 'recursive-99']))
            ->assertSessionHasErrors('tier_interest');
    }
}
