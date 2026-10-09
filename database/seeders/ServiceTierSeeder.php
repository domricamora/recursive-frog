<?php

namespace Database\Seeders;

use App\Models\ServiceFeature;
use App\Models\ServiceTier;
use Illuminate\Database\Seeder;

/**
 * Tier copy comes from plan.md #9, #16 and #17.
 *
 * IMPORTANT: package inclusions in the source offer were still being
 * finalised, so anything not explicitly stated by the source is seeded as
 * `draft`. Draft items are hidden from the public site and can be promoted
 * from Admin > Services once approved (plan.md acceptance #14).
 */
class ServiceTierSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->tiers() as $attributes) {
            $features = $attributes['features'];
            unset($attributes['features']);

            $tier = ServiceTier::updateOrCreate(['slug' => $attributes['slug']], $attributes);

            foreach ($features as $order => $feature) {
                ServiceFeature::updateOrCreate(
                    ['service_tier_id' => $tier->id, 'name' => $feature['name']],
                    [...$feature, 'display_order' => $order + 1],
                );
            }
        }
    }

    /** @return array<int, array<string, mixed>> */
    protected function tiers(): array
    {
        return array_merge($this->recursive3(), $this->recursive5(), $this->recursive7());
    }

    /** @return array<int, array<string, mixed>> */
    protected function recursive3(): array
    {
        return [[
            'name' => 'Recursive 3',
            'slug' => 'recursive-3',
            'model' => 'Basic model',
            'includes' => 'Website',
            'badge' => 'Entry',
            'summary' => 'A professional business website built around how customers find and choose your services, giving your business a digital foundation it owns.',
            'short_description' => 'A website that gives your business a clear online presence and a digital foundation you own.',
            'description' => 'Recursive 3 is the foundation: a business website designed to turn interest into a useful next step. '
                .'It is built to be handed over cleanly — the site, the code and the data belong to your business once paid in full.',
            'audience' => 'A business with little or no online presence, or a website that makes it hard for customers to take the next step, '
                .'and wants a credible foundation before investing in internal software.',
            'problems_solved' => "Inquiries scattered across Messenger and Viber with nothing to show for it.\n"
                .'A website that lists services but does not help customers enquire, book or buy.\n'
                .'No reliable way to see how people are finding the business.',
            'implementation' => "1. We look at how inquiries and bookings reach you today.\n"
                ."2. We map the booking journey and design the site around it.\n"
                ."3. We build and test on phones, tablets and computers.\n"
                .'4. We launch, then keep sending written progress updates.',
            'example_project' => 'VirtuaCore — a brand site built around an animated WebGL2 homepage, showing how far a business website can be pushed visually.',
            'cta_label' => 'Discuss Recursive 3',
            'highlights' => ['Booking-focused', 'You own it', 'Weekly updates', 'One-day replies'],
            'display_order' => 1,
            'is_active' => true,
            'features' => [
                [
                    'name' => 'Customer-focused business website',
                    'description' => 'Designed around how customers discover, choose and enquire about your services.',
                    'group' => 'Website',
                    'status' => ServiceTier::STATUS_CONFIRMED,
                ],
                [
                    'name' => 'Ownership of code and data',
                    'description' => 'Website, code and data belong to your business once paid in full.',
                    'group' => 'Ownership',
                    'status' => ServiceTier::STATUS_CONFIRMED,
                ],
                [
                    'name' => 'Weekly written progress updates',
                    'description' => 'A short update and a link to try the latest version.',
                    'group' => 'Process',
                    'status' => ServiceTier::STATUS_CONFIRMED,
                ],
                [
                    'name' => 'Replies within one business day',
                    'description' => 'Questions answered within one business day.',
                    'group' => 'Process',
                    'status' => ServiceTier::STATUS_CONFIRMED,
                ],
                [
                    'name' => 'Testing on phones, tablets and computers',
                    'description' => 'Checked across device sizes before launch.',
                    'group' => 'Process',
                    'status' => ServiceTier::STATUS_CONFIRMED,
                ],
                [
                    'name' => 'Service and product pages',
                    'description' => 'Structured pages for the services or products your business offers.',
                    'group' => 'Website',
                    'status' => ServiceTier::STATUS_DRAFT,
                ],
                [
                    'name' => 'Enquiry and booking forms',
                    'description' => 'Forms that route enquiries into a single inbox instead of personal chats.',
                    'group' => 'Website',
                    'status' => ServiceTier::STATUS_DRAFT,
                ],
                [
                    'name' => 'Analytics and Google Tag Manager setup',
                    'description' => 'Conversion tracking wired up from day one.',
                    'group' => 'Measurement',
                    'status' => ServiceTier::STATUS_DRAFT,
                ],
            ],
        ]];
    }

    /** @return array<int, array<string, mixed>> */
    protected function recursive5(): array
    {
        return [[
            'name' => 'Recursive 5',
            'slug' => 'recursive-5',
            'model' => 'Pro model',
            'includes' => 'Website + SaaS',
            'badge' => 'For growing operations',
            'summary' => 'Bring your public website and custom business software together as your operation grows.',
            'short_description' => 'Connect your public website with custom software designed around the operation behind it.',
            'description' => 'Recursive 5 pairs your public website with the software that runs the business behind it — '
                .'so the front door and the back office are finally the same system instead of two disconnected things.',
            'audience' => 'A growing business with real customer, order or booking volume, where staff are handling schedules, records and stock '
                .'across notebooks, paper forms and chat threads.',
            'problems_solved' => "Orders or bookings missed because information is scattered across channels.\n"
                .'Customer, project or operational records split between notebooks and files.'."\n"
                .'Not knowing stock levels until something runs out.'."\n"
                .'Finding out how the month went only at month-end.',
            'implementation' => "1. We learn how inquiries, bookings, records, payments and reporting work today.\n"
                ."2. We define the scope and the right split between website and software.\n"
                ."3. We build and test the workflows with your staff in mind.\n"
                .'4. We launch with ongoing support and clear communication.',
            'example_project' => 'DeskPulse — a multi-tenant time-tracking SaaS built on Laravel, with separate dashboards per role, a pattern used in custom business software.',
            'cta_label' => 'Discuss Recursive 5',
            'highlights' => ['Website + SaaS', 'Role-based access', 'Business records', 'Stock visibility'],
            'display_order' => 2,
            'is_active' => true,
            'features' => [
                [
                    'name' => 'Everything in Recursive 3',
                    'description' => 'The full website foundation, carried forward.',
                    'group' => 'Included',
                    'status' => ServiceTier::STATUS_CONFIRMED,
                ],
                [
                    'name' => 'Business software connected to the website',
                    'description' => 'One system across the public site and day-to-day operations.',
                    'group' => 'Software',
                    'status' => ServiceTier::STATUS_CONFIRMED,
                ],
                [
                    'name' => 'Appointment and booking management',
                    'description' => 'A shared schedule instead of scattered personal calendars.',
                    'group' => 'Software',
                    'status' => ServiceTier::STATUS_CONFIRMED,
                ],
                [
                    'name' => 'Customer and operational records',
                    'description' => 'Business records kept in the system rather than notebooks and scattered files.',
                    'group' => 'Software',
                    'status' => ServiceTier::STATUS_CONFIRMED,
                ],
                [
                    'name' => 'Access levels per role',
                    'description' => 'Role-based permissions so staff only see what they need.',
                    'group' => 'Security',
                    'status' => ServiceTier::STATUS_CONFIRMED,
                ],
                [
                    'name' => 'Stock and inventory visibility',
                    'description' => 'Know what is on hand before it runs out.',
                    'group' => 'Software',
                    'status' => ServiceTier::STATUS_CONFIRMED,
                ],
                [
                    'name' => 'Dashboards and reporting',
                    'description' => 'See the numbers during the month, not after it.',
                    'group' => 'Reporting',
                    'status' => ServiceTier::STATUS_DRAFT,
                ],
                [
                    'name' => 'Automated reminders and deposits',
                    'description' => 'Reduce no-shows with confirmations and deposits.',
                    'group' => 'Automation',
                    'status' => ServiceTier::STATUS_DRAFT,
                ],
                [
                    'name' => 'CRM integration',
                    'description' => 'Keep enquiries and follow-up in sync with your CRM.',
                    'group' => 'Integration',
                    'status' => ServiceTier::STATUS_DRAFT,
                ],
            ],
        ]];
    }

    /** @return array<int, array<string, mixed>> */
    protected function recursive7(): array
    {
        return [[
            'name' => 'Recursive 7',
            'slug' => 'recursive-7',
            'model' => 'Champion model',
            'includes' => 'Website + SaaS + AI automation',
            'badge' => 'Full system',
            'summary' => 'Add administrative automation to the website and software foundation.',
            'short_description' => 'Add administrative automation to the website and software foundation.',
            'description' => 'Recursive 7 adds administrative automation on top of the website and software. '
                .'AI drafts and suggests; a person always reviews and approves before anything happens. '
                .'AI handles administrative work only; people review its suggestions before action. It does not make clinical, financial, hiring or other high-stakes decisions.',
            'audience' => 'An established business where repetitive administrative work has become the bottleneck, and where '
                .'the team needs systems that scale without adding headcount for routine paperwork.',
            'problems_solved' => "Customers who buy once and never come back.\n"
                .'Repetitive administrative work consuming staff hours.'."\n"
                .'Bookings, order updates and follow-up handled by memory.'."\n"
                .'Growth that outpaces the current way of working.',
            'implementation' => "1. We map the administrative work that repeats every week.\n"
                ."2. We agree which steps AI may draft and which stay manual.\n"
                ."3. We build the approval workflow — AI suggests, a person approves.\n"
                .'4. We test, launch and document the workflow for your team.',
            'example_project' => 'Paid Media Manager — AI-assisted suggestions where Claude drafts recommendations and a person approves every change. VT Payroll Automation imports tracker data, calculates pay and emails payslips.',
            'cta_label' => 'Discuss Recursive 7',
            'highlights' => ['AI drafts', 'Human approves', 'Admin only', 'Scales with you'],
            'display_order' => 3,
            'is_active' => true,
            'features' => [
                [
                    'name' => 'Everything in Recursive 5',
                    'description' => 'The website and business software foundation, carried forward.',
                    'group' => 'Included',
                    'status' => ServiceTier::STATUS_CONFIRMED,
                ],
                [
                    'name' => 'Administrative automation',
                    'description' => 'Repetitive paperwork handled by system, not by hand.',
                    'group' => 'Automation',
                    'status' => ServiceTier::STATUS_CONFIRMED,
                ],
                [
                    'name' => 'AI drafts, a person approves',
                    'description' => 'AI produces suggestions; a human reviews and approves every change.',
                    'group' => 'Responsible AI',
                    'status' => ServiceTier::STATUS_CONFIRMED,
                ],
                [
                    'name' => 'AI for administrative work only',
                    'description' => 'People approve its suggestions; AI does not make clinical, financial, hiring or other high-stakes decisions.',
                    'group' => 'Responsible AI',
                    'status' => ServiceTier::STATUS_CONFIRMED,
                ],
                [
                    'name' => 'Consent-first processes',
                    'description' => 'Consent recorded before anything is shared or automated.',
                    'group' => 'Security',
                    'status' => ServiceTier::STATUS_CONFIRMED,
                ],
                [
                    'name' => 'Activity logs on sensitive actions',
                    'description' => 'A record of who did what, especially around sensitive customer and business data.',
                    'group' => 'Security',
                    'status' => ServiceTier::STATUS_CONFIRMED,
                ],
                [
                    'name' => 'Automated reminders and deposit flows',
                    'description' => 'Cut no-shows with confirmations, reminders and deposits.',
                    'group' => 'Automation',
                    'status' => ServiceTier::STATUS_DRAFT,
                ],
                [
                    'name' => 'Automated reporting summaries',
                    'description' => 'Regular summaries instead of month-end surprises.',
                    'group' => 'Automation',
                    'status' => ServiceTier::STATUS_DRAFT,
                ],
                [
                    'name' => 'Imports and scheduled jobs',
                    'description' => 'Bring data in from trackers and spreadsheets automatically.',
                    'group' => 'Automation',
                    'status' => ServiceTier::STATUS_DRAFT,
                ],
            ],
        ]];
    }
}
