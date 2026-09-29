<?php

namespace Database\Seeders;

use App\Models\TeamMember;
use Illuminate\Database\Seeder;

/** Team from plan.md #14 and #15. */
class TeamSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->members() as $order => $member) {
            TeamMember::updateOrCreate(['role' => $member['role']], [...$member, 'display_order' => $order + 1]);
        }
    }

    /** @return array<int, array<string, mixed>> */
    protected function members(): array
    {
        return [
            [
                'name' => null,
                'role' => 'Sales',
                'focus' => 'Finds the right fit before anything is built',
                'bio' => 'Understands your goals, helps you choose the right tier and scopes it clearly.',
                'is_specialist' => false,
                'published' => true,
            ],
            [
                'name' => null,
                'role' => 'Front',
                'focus' => 'Your day-to-day contact',
                'bio' => 'Your day-to-day contact for demos, onboarding and regular progress updates.',
                'is_specialist' => false,
                'published' => true,
            ],
            [
                'name' => null,
                'role' => 'Tech',
                'focus' => 'Builds, maintains and secures',
                'bio' => 'Builds and maintains the website, software, automation, security and support.',
                'is_specialist' => false,
                'published' => true,
            ],
            [
                'name' => 'Nick Ricamora',
                'role' => 'Technical Specialist',
                'focus' => 'Full-stack Laravel and React developer, Cebu City',
                'bio' => '20+ years building for the web, including a DOST Best Web Design award. Builds booking platforms with '
                    .'local payments, multi-tenant SaaS with separate dashboards per role, and AI workflows where a person approves '
                    .'every change.',
                'highlights' => [
                    '20+ years building for the web',
                    'DOST Best Web Design award',
                    'Booking platforms with local payments',
                    'PayMongo, GCash, Maya, cards, Xendit and PayPal',
                    'Multi-tenant SaaS with separate dashboards per role',
                    'AI workflows where a person approves changes',
                    'HubSpot workflows',
                    'GA4 and Google Tag Manager tracking',
                    'Laravel, React, PHP, MySQL, Shopify and WordPress',
                ],
                'is_specialist' => true,
                'published' => true,
            ],
        ];
    }
}
