<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

/** FAQ content from plan.md #21. */
class FaqSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->faqs() as $order => $faq) {
            Faq::updateOrCreate(['question' => $faq['question']], [...$faq, 'display_order' => $order + 1]);
        }
    }

    /** @return array<int, array<string, string>> */
    protected function faqs(): array
    {
        return [
            [
                'question' => 'What does Recursive Frog build?',
                'answer' => 'Websites, clinic software and administrative automation, depending on the selected service tier. '
                    .'Recursive 3 is the website, Recursive 5 adds clinic software, and Recursive 7 adds administrative automation.',
                'category' => 'Services',
            ],
            [
                'question' => 'Which clinics do you work with?',
                'answer' => 'The current offer is focused on aesthetics clinics in Cebu City and surrounding areas.',
                'category' => 'Services',
            ],
            [
                'question' => 'Do we own the website?',
                'answer' => 'Yes. The offer states that the website, code and data belong to the clinic once paid in full. '
                    .'There is no lock-in.',
                'category' => 'Ownership',
            ],
            [
                'question' => 'Do you provide SaaS?',
                'answer' => 'The Pro and Champion models include SaaS as currently described in the offer — '
                    .'software designed to support the operation behind the public website.',
                'category' => 'Services',
            ],
            [
                'question' => 'Does AI make medical decisions?',
                'answer' => 'No. The stated approach is that AI handles administrative work only and does not diagnose or recommend '
                    .'treatments. AI drafts suggestions, a person reviews, and the system only acts after a person approves.',
                'category' => 'Responsible AI',
            ],
            [
                'question' => 'How often do we receive progress updates?',
                'answer' => 'The offer specifies weekly progress updates: a short written update and a link to try the latest version.',
                'category' => 'Process',
            ],
            [
                'question' => 'How quickly do you respond?',
                'answer' => 'The offer specifies that questions are answered within one business day.',
                'category' => 'Process',
            ],
            [
                'question' => 'Is the website tested on mobile?',
                'answer' => 'Yes. The stated process includes testing on phones, tablets and computers before launch.',
                'category' => 'Process',
            ],
            [
                'question' => 'How is patient information protected?',
                'answer' => 'Access levels per role, activity logs and consent-first processes. We store only the information we '
                    .'need, and this website never asks you to submit medical details.',
                'category' => 'Security',
            ],
            [
                'question' => 'What happens after launch?',
                'answer' => 'You keep continued support and clear communication. Weekly written updates continue, and questions '
                    .'are answered within one business day.',
                'category' => 'Process',
            ],
        ];
    }
}
