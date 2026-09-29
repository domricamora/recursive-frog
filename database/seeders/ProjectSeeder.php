<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\ProjectFeature;
use Illuminate\Database\Seeder;

/**
 * Portfolio from plan.md #12 and #19. Every statement here is taken from the
 * source offer — no invented metrics, client claims or results (plan.md #19).
 */
class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->projects() as $attributes) {
            $features = $attributes['features'] ?? [];
            unset($attributes['features']);

            $project = Project::updateOrCreate(['slug' => $attributes['slug']], $attributes);

            foreach ($features as $order => $feature) {
                ProjectFeature::updateOrCreate(
                    ['project_id' => $project->id, 'name' => $feature['name']],
                    ['description' => $feature['description'] ?? null, 'display_order' => $order + 1],
                );
            }
        }
    }

    /** @return array<int, array<string, mixed>> */
    protected function projects(): array
    {
        return array_merge($this->platforms(), $this->webAndProduct(), $this->aiAndAutomation());
    }

    /** @return array<int, array<string, mixed>> */
    protected function platforms(): array
    {
        return [
            [
                'name' => 'Cover & Keys',
                'slug' => 'cover-and-keys',
                'category' => 'Booking platform',
                'summary' => 'A booking platform for stays and restaurants across the Philippines.',
                'overview' => 'A booking platform covering stays and restaurants across the Philippines, with payment and host '
                    .'operations handled in one place.',
                'business_problem' => 'A marketplace where guests needed to book and pay in a single flow, while hosts and '
                    .'property managers each needed their own view of the business.',
                'solution' => 'A single booking flow that runs from search to payment, with guest checkout that does not require '
                    .'an account, and separate dashboards for each host type.',
                'technologies' => ['Booking engine', 'Payments', 'Dashboards'],
                'integrations' => ['PayMongo', 'GCash', 'Maya', 'Cards', 'PayPal'],
                'featured' => true,
                'published' => true,
                'display_order' => 1,
                'features' => [
                    ['name' => 'Book and pay in one flow', 'description' => 'No handoff between reservation and payment.'],
                    ['name' => 'Guest checkout without an account', 'description' => 'Conversion stays high; no forced sign-up.'],
                    ['name' => 'Multiple host types', 'description' => 'Property owners and restaurant operators handled differently.'],
                    ['name' => 'Separate dashboards', 'description' => 'Each host type gets the view that matches how they operate.'],
                    ['name' => 'Local and international payments', 'description' => 'PayMongo, GCash, Maya, cards and PayPal in the same checkout.'],
                ],
            ],
            [
                'name' => 'RGE Hotel',
                'slug' => 'rge-hotel',
                'category' => 'Booking engine',
                'summary' => 'Booking engine with Xendit payments and a role-based back office.',
                'overview' => 'A hotel booking engine with Xendit payments and a back office that organises staff access by role.',
                'business_problem' => 'Direct bookings needed a payment path and an internal back office that did not depend on a third-party marketplace.',
                'solution' => 'A booking engine wired to Xendit, backed by a role-based back office so each staff member sees only the areas they work in.',
                'technologies' => ['Booking engine', 'Role-based access'],
                'integrations' => ['Xendit'],
                'featured' => true,
                'published' => true,
                'display_order' => 2,
                'features' => [
                    ['name' => 'Direct booking engine', 'description' => 'Bookings handled on the hotel’s own channel.'],
                    ['name' => 'Xendit payments', 'description' => 'Local payment methods integrated into checkout.'],
                    ['name' => 'Role-based back office', 'description' => 'Access scoped per role instead of one shared login.'],
                ],
            ],
        ];
    }

    /** @return array<int, array<string, mixed>> */
    protected function webAndProduct(): array
    {
        return [
            [
                'name' => 'VirtuaCore',
                'slug' => 'virtuacore',
                'category' => 'Brand site',
                'summary' => 'Brand site featuring an animated WebGL2 homepage.',
                'overview' => 'A brand site whose homepage is an animated WebGL2 experience — proof that a marketing site can carry '
                    .'real technical weight without becoming a brochure.',
                'business_problem' => 'A brand that needed to be memorable on first visit rather than blending into every other clinic or studio site.',
                'solution' => 'A WebGL2-driven homepage paired with an ordinary, fast content structure underneath.',
                'technologies' => ['WebGL2', 'Front-end animation'],
                'integrations' => [],
                'featured' => true,
                'published' => true,
                'display_order' => 3,
                'features' => [
                    ['name' => 'Animated WebGL2 homepage', 'description' => 'A real-time rendered experience rather than a static hero image.'],
                    ['name' => 'Fast content pages underneath', 'description' => 'The rest of the site stays quick to load and easy to read.'],
                ],
            ],
            [
                'name' => 'DeskPulse',
                'slug' => 'deskpulse',
                'category' => 'Multi-tenant SaaS',
                'summary' => 'Multi-tenant time-tracking SaaS built on Laravel.',
                'overview' => 'A multi-tenant time-tracking SaaS built on Laravel, where each organisation works inside its own '
                    .'isolated data space and its own dashboards.',
                'business_problem' => 'Time tracking products usually either bolt onto one company or give every team the same interface regardless of role.',
                'solution' => 'Multi-tenant architecture in Laravel, with per-tenant data isolation and dashboards tailored to each role.',
                'technologies' => ['Laravel', 'PHP', 'MySQL', 'Multi-tenant'],
                'integrations' => [],
                'featured' => true,
                'published' => true,
                'display_order' => 4,
                'features' => [
                    ['name' => 'Multi-tenant architecture', 'description' => 'Each organisation isolated in its own data space.'],
                    ['name' => 'Separate dashboards per role', 'description' => 'Different interfaces for different jobs.'],
                    ['name' => 'Time tracking core', 'description' => 'Entries, timesheets and reporting handled in the system.'],
                ],
            ],
        ];
    }

    /** @return array<int, array<string, mixed>> */
    protected function aiAndAutomation(): array
    {
        return [
            [
                'name' => 'Paid Media Manager',
                'slug' => 'paid-media-manager',
                'category' => 'AI-assisted tooling',
                'summary' => 'AI-assisted suggestions where Claude drafts recommendations and a person approves every change.',
                'overview' => 'A paid media management tool where Claude drafts recommendations and a person reviews and approves '
                    .'every change before it goes live. This is the clearest example of the "AI does admin only" principle in practice.',
                'business_problem' => 'Campaign recommendations were either manual and slow, or automated and unaccountable.',
                'solution' => 'AI produces a draft recommendation. A human reviews it, edits it, and approves it. Nothing is applied without that approval step.',
                'technologies' => ['Claude API', 'Approval workflow'],
                'integrations' => ['Claude API'],
                'featured' => true,
                'published' => true,
                'display_order' => 5,
                'features' => [
                    ['name' => 'AI drafts the recommendation', 'description' => 'Claude prepares a written suggestion for review.'],
                    ['name' => 'A person approves every change', 'description' => 'No automatic publishing — approval is a required step.'],
                    ['name' => 'Full activity history', 'description' => 'Who suggested what, who approved it, and when.'],
                ],
            ],
            [
                'name' => 'VT Payroll Automation',
                'slug' => 'vt-payroll-automation',
                'category' => 'Automation',
                'summary' => 'Imports tracker data, calculates pay and emails payslips.',
                'overview' => 'An automation that removes the monthly payroll chore: it imports time-tracking data, calculates the '
                    .'figures, and emails payslips to the team.',
                'business_problem' => 'Payroll was assembled by hand from time-tracking exports every month.',
                'solution' => 'Import the tracker data, calculate the pay, and send payslips automatically — with a person able to check the result before it goes out.',
                'technologies' => ['Automation', 'Reporting', 'Email'],
                'integrations' => ['Email', 'Time tracking'],
                'featured' => true,
                'published' => true,
                'display_order' => 6,
                'features' => [
                    ['name' => 'Tracker data import', 'description' => 'Hours pulled from the existing tracker.'],
                    ['name' => 'Automated pay calculation', 'description' => 'Figures calculated by the system, not by hand.'],
                    ['name' => 'Payslips emailed automatically', 'description' => 'Each person receives their own payslip.'],
                ],
            ],
            [
                'name' => 'Virtual Teammate',
                'slug' => 'virtual-teammate',
                'category' => 'CRM and analytics',
                'summary' => 'HubSpot workflows and GA4 lead tracking.',
                'overview' => 'A lightweight operations layer that keeps HubSpot workflows running and GA4 lead tracking honest, '
                    .'so the pipeline reflects what actually happened.',
                'business_problem' => 'Leads were being recorded inconsistently, and follow-up depended on someone remembering.',
                'solution' => 'HubSpot workflows handle the routine follow-up steps, while GA4 tracking records where leads genuinely came from.',
                'technologies' => ['HubSpot', 'GA4', 'Google Tag Manager'],
                'integrations' => ['HubSpot', 'Google Analytics 4', 'Google Tag Manager'],
                'featured' => false,
                'published' => true,
                'display_order' => 7,
                'features' => [
                    ['name' => 'HubSpot workflows', 'description' => 'Routine follow-up runs in the CRM.'],
                    ['name' => 'GA4 lead tracking', 'description' => 'Lead sources measured rather than guessed.'],
                    ['name' => 'Tag Manager setup', 'description' => 'Tags managed centrally.'],
                ],
            ],
        ];
    }
}
