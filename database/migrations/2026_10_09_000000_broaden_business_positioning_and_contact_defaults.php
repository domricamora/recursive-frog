<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const SETTINGS = [
        'email' => ['hello@recursivefrog.ph', 'admin@recursive-frog.com'],
        'phone' => ['+63 917 000 0000', '09758242517'],
        'seo_title' => [
            'Recursive Frog — Digital Systems for Cebu Aesthetics Clinics',
            'Recursive Frog | Websites, Software & Automation for Cebu Businesses',
        ],
        'seo_description' => [
            'Build a better booking experience, automate repetitive admin work, and give your aesthetics clinic the digital systems it needs to grow.',
            'Websites, custom software and workflow automation for Cebu businesses in hospitality, e-commerce, services and more.',
        ],
    ];

    private const TIERS = [
        'recursive-3' => [
            'summary' => [
                'A professional clinic website built around your booking journey, giving your clinic a digital foundation it owns.',
                'A professional business website built around how customers find and choose your services, giving your business a digital foundation it owns.',
            ],
            'short_description' => [
                'A booking-focused clinic website that gives your clinic a professional online presence and a digital foundation you own.',
                'A website that gives your business a clear online presence and a digital foundation you own.',
            ],
            'description' => [
                'Recursive 3 is the foundation: a clinic website designed to invite bookings rather than just describe them. It is built to be handed over cleanly — the site, the code and the data belong to your clinic once paid in full.',
                'Recursive 3 is the foundation: a business website designed to turn interest into a useful next step. It is built to be handed over cleanly — the site, the code and the data belong to your business once paid in full.',
            ],
            'audience' => [
                'A clinic that currently has little or no online presence, or a website that is hard to book from, and wants a credible foundation before investing in internal software.',
                'A business with little or no online presence, or a website that makes it hard for customers to take the next step, and wants a credible foundation before investing in internal software.',
            ],
            'problems_solved' => [
                "Inquiries scattered across Messenger and Viber with nothing to show for it.\nA website that describes treatments but does not help anyone book.\nNo reliable way to see how people are reaching the clinic at all.",
                "Inquiries scattered across Messenger and Viber with nothing to show for it.\nA website that lists services but does not help customers enquire, book or buy.\nNo reliable way to see how people are finding the business.",
            ],
            'example_project' => [
                'VirtuaCore — a brand site built around an animated WebGL2 homepage, showing how far a clinic-facing site can be pushed visually.',
                'VirtuaCore — a brand site built around an animated WebGL2 homepage, showing how far a business website can be pushed visually.',
            ],
        ],
        'recursive-5' => [
            'badge' => ['Most clinics start here', 'For growing operations'],
            'summary' => [
                'Bring your public website and clinic software together as your operation grows.',
                'Bring your public website and custom business software together as your operation grows.',
            ],
            'short_description' => [
                'Connect the public-facing clinic website with software designed to support the operation behind it.',
                'Connect your public website with custom software designed around the operation behind it.',
            ],
            'description' => [
                'Recursive 5 pairs the booking experience with the software that runs the clinic behind it — so the front door and the back office are finally the same system instead of two disconnected things.',
                'Recursive 5 pairs your public website with the software that runs the business behind it — so the front door and the back office are finally the same system instead of two disconnected things.',
            ],
            'audience' => [
                'A growing clinic with real booking volume, where staff are handling scheduling, records and stock across notebooks, paper forms and chat threads.',
                'A growing business with real customer, order or booking volume, where staff are handling schedules, records and stock across notebooks, paper forms and chat threads.',
            ],
            'problems_solved' => [
                "Appointments missed with no reminders or deposits in place.\nPatient history split between notebooks and paper forms.\nNot knowing stock levels until something runs out.\nFinding out how the month went only at month-end.",
                "Orders or bookings missed because information is scattered across channels.\nCustomer, project or operational records split between notebooks and files.\nNot knowing stock levels until something runs out.\nFinding out how the month went only at month-end.",
            ],
            'example_project' => [
                'DeskPulse — a multi-tenant time-tracking SaaS built on Laravel, with separate dashboards per role, the pattern clinic software follows.',
                'DeskPulse — a multi-tenant time-tracking SaaS built on Laravel, with separate dashboards per role, a pattern used in custom business software.',
            ],
        ],
        'recursive-7' => [
            'description' => [
                'Recursive 7 adds administrative automation on top of the website and software. AI drafts and suggests; a person always reviews and approves before anything happens. AI does admin only — it does not diagnose, recommend treatments or make clinical decisions.',
                'Recursive 7 adds administrative automation on top of the website and software. AI handles administrative work only; people review its suggestions before action. It does not make clinical, financial, hiring or other high-stakes decisions.',
            ],
            'audience' => [
                'An established clinic where repetitive administrative work has become the bottleneck, and where the team needs systems that scale without adding headcount for routine paperwork.',
                'An established business where repetitive administrative work has become the bottleneck, and where the team needs systems that scale without adding headcount for routine paperwork.',
            ],
            'problems_solved' => [
                "Clients who visit once and never come back.\nRepetitive administrative work consuming staff hours.\nAppointments, reminders and follow-up handled by memory.\nGrowth that outpaces the current way of working.",
                "Customers who buy once and never come back.\nRepetitive administrative work consuming staff hours.\nBookings, order updates and follow-up handled by memory.\nGrowth that outpaces the current way of working.",
            ],
        ],
    ];

    private const FEATURES = [
        'recursive-3' => [
            [
                ['Booking-focused clinic website', 'Designed around how a patient actually books, not just a service list.'],
                ['Customer-focused business website', 'Designed around how customers discover, choose and enquire about your services.'],
            ],
            [
                ['Ownership of code and data', 'Website, code and data belong to your clinic once paid in full.'],
                ['Ownership of code and data', 'Website, code and data belong to your business once paid in full.'],
            ],
            [
                ['Treatment and service pages', 'Structured pages for each service the clinic offers.'],
                ['Service and product pages', 'Structured pages for the services or products your business offers.'],
            ],
        ],
        'recursive-5' => [
            [
                ['Clinic software connected to the website', 'One system across the public site and day-to-day operations.'],
                ['Business software connected to the website', 'One system across the public site and day-to-day operations.'],
            ],
            [
                ['Patient history in one place', 'Records kept in the system rather than notebooks and paper forms.'],
                ['Customer and operational records', 'Business records kept in the system rather than notebooks and scattered files.'],
            ],
        ],
        'recursive-7' => [
            [
                ['Everything in Recursive 5', 'The website and clinic software foundation, carried forward.'],
                ['Everything in Recursive 5', 'The website and business software foundation, carried forward.'],
            ],
            [
                ['AI does admin only', 'No diagnosis, no treatment recommendations, no clinical decisions.'],
                ['AI for administrative work only', 'People approve its suggestions; AI does not make clinical, financial, hiring or other high-stakes decisions.'],
            ],
            [
                ['Activity logs on sensitive actions', 'A record of who did what, especially around patient data.'],
                ['Activity logs on sensitive actions', 'A record of who did what, especially around sensitive customer and business data.'],
            ],
        ],
    ];

    private const FAQS = [
        [
            'question' => [
                'What does Recursive Frog build?',
                'What does Recursive Frog build?',
            ],
            'answer' => [
                'Websites, clinic software and administrative automation, depending on the selected service tier. Recursive 3 is the website, Recursive 5 adds clinic software, and Recursive 7 adds administrative automation.',
                'Websites, custom business software and administrative automation, depending on the selected service tier. Recursive 3 is the website, Recursive 5 adds business software, and Recursive 7 adds administrative automation.',
            ],
        ],
        [
            'question' => [
                'Which clinics do you work with?',
                'Which industries do you work with?',
            ],
            'answer' => [
                'The current offer is focused on aesthetics clinics in Cebu City and surrounding areas.',
                'We build for businesses across industries, including aesthetics, hospitality, e-commerce and service businesses. The system is scoped around your customers, workflows and goals.',
            ],
        ],
        [
            'question' => ['Do we own the website?', 'Do we own the website?'],
            'answer' => [
                'Yes. The offer states that the website, code and data belong to the clinic once paid in full. There is no lock-in.',
                'Yes. The website, code and data belong to your business once paid in full. There is no lock-in.',
            ],
        ],
        [
            'question' => [
                'Does AI make medical decisions?',
                'Does AI make decisions on its own?',
            ],
            'answer' => [
                'No. The stated approach is that AI handles administrative work only and does not diagnose or recommend treatments. AI drafts suggestions, a person reviews, and the system only acts after a person approves.',
                'No. AI handles administrative work only. A person reviews and approves suggestions before action, and AI does not make clinical, financial, hiring or other high-stakes decisions.',
            ],
        ],
        [
            'question' => [
                'How is patient information protected?',
                'How is sensitive business data protected?',
            ],
            'answer' => [
                'Access levels per role, activity logs and consent-first processes. We store only the information we need, and this website never asks you to submit medical details.',
                'Access levels per role, activity logs and consent-first processes. We store only the information we need, and this website never asks you to submit confidential customer, employee or patient records.',
            ],
        ],
    ];

    public function up(): void
    {
        $this->updateSettings();
        $this->updateTierCopy();
        $this->updateFeatureCopy();
        $this->updateTierHighlights();
        $this->updateFaqCopy();
    }

    public function down(): void
    {
        $this->updateFaqCopy(reverse: true);
        $this->updateTierHighlights(reverse: true);
        $this->updateFeatureCopy(reverse: true);
        $this->updateTierCopy(reverse: true);
        $this->updateSettings(reverse: true);
    }

    private function updateSettings(bool $reverse = false): void
    {
        foreach (self::SETTINGS as $key => [$before, $after]) {
            $from = $reverse ? $after : $before;
            $to = $reverse ? $before : $after;

            $query = DB::table('site_settings')->where('key', $key);

            if ($reverse) {
                $query->where('value', $from);
            }

            $query->update(['value' => $to, 'updated_at' => now()]);
        }
    }

    private function updateTierCopy(bool $reverse = false): void
    {
        foreach (self::TIERS as $slug => $fields) {
            foreach ($fields as $column => [$before, $after]) {
                $from = $reverse ? $after : $before;
                $to = $reverse ? $before : $after;

                DB::table('service_tiers')
                    ->where('slug', $slug)
                    ->where($column, $from)
                    ->update([$column => $to, 'updated_at' => now()]);
            }
        }
    }

    private function updateFeatureCopy(bool $reverse = false): void
    {
        foreach (self::FEATURES as $slug => $features) {
            $tierId = DB::table('service_tiers')->where('slug', $slug)->value('id');

            if ($tierId === null) {
                continue;
            }

            foreach ($features as [$before, $after]) {
                $from = $reverse ? $after : $before;
                $to = $reverse ? $before : $after;

                DB::table('service_features')
                    ->where('service_tier_id', $tierId)
                    ->where('name', $from[0])
                    ->where('description', $from[1])
                    ->update([
                        'name' => $to[0],
                        'description' => $to[1],
                        'updated_at' => now(),
                    ]);
            }
        }
    }

    private function updateTierHighlights(bool $reverse = false): void
    {
        $before = ['Website + SaaS', 'Role-based access', 'Patient history', 'Stock visibility'];
        $after = ['Website + SaaS', 'Role-based access', 'Business records', 'Stock visibility'];
        $from = $reverse ? $after : $before;
        $to = $reverse ? $before : $after;
        $tier = DB::table('service_tiers')->where('slug', 'recursive-5')->first(['id', 'highlights']);

        if ($tier !== null && json_decode((string) $tier->highlights, true) === $from) {
            DB::table('service_tiers')
                ->where('id', $tier->id)
                ->update(['highlights' => json_encode($to), 'updated_at' => now()]);
        }
    }

    private function updateFaqCopy(bool $reverse = false): void
    {
        foreach (self::FAQS as $faq) {
            $fromQuestion = $reverse ? $faq['question'][1] : $faq['question'][0];
            $toQuestion = $reverse ? $faq['question'][0] : $faq['question'][1];
            $fromAnswer = $reverse ? $faq['answer'][1] : $faq['answer'][0];
            $toAnswer = $reverse ? $faq['answer'][0] : $faq['answer'][1];

            DB::table('faqs')
                ->where('question', $fromQuestion)
                ->where('answer', $fromAnswer)
                ->update([
                    'question' => $toQuestion,
                    'answer' => $toAnswer,
                    'updated_at' => now(),
                ]);
        }
    }
};
