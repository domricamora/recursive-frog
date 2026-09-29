<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Faq;
use App\Models\Lead;
use App\Models\Project;
use App\Models\ServiceTier;
use App\Models\TeamMember;
use App\Models\User;
use App\Support\HubSpot;
use Illuminate\View\View;

/** Admin overview (plan.md #26). */
class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $tierInterest = Lead::query()
            ->selectRaw('tier_interest, tier_label, count(*) as total')
            ->groupBy('tier_interest', 'tier_label')
            ->orderByDesc('total')
            ->get();

        $statusCounts = collect(Lead::statuses())
            ->map(fn ($label, $key) => [
                'status' => $key,
                'label' => $label,
                'count' => Lead::query()->where('status', $key)->count(),
            ]);

        return view('admin.dashboard', [
            'stats' => [
                'totalLeads' => Lead::query()->count(),
                'newLeads' => Lead::query()->where('status', Lead::STATUS_NEW)->count(),
                'thisMonth' => Lead::query()->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->count(),
                'qualified' => Lead::query()->whereIn('status', [Lead::STATUS_QUALIFIED, Lead::STATUS_PROPOSAL, Lead::STATUS_WON])->count(),
                'tiers' => ServiceTier::query()->count(),
                'projects' => Project::query()->published()->count(),
                'faqs' => Faq::query()->published()->count(),
                'team' => TeamMember::query()->published()->count(),
            ],
            'recentLeads' => Lead::query()->latest()->take(8)->get(),
            'statusCounts' => $statusCounts,
            'tierInterest' => $tierInterest,
            'team' => User::query()->get(['id', 'name', 'email', 'role']),
            'auditLogs' => AuditLog::query()->with('user')->latest()->take(8)->get(),
            'hubspotEnabled' => HubSpot::enabled(),
            'integrationStatus' => [
                'HubSpot CRM' => HubSpot::enabled(),
                'GA4' => (bool) config('services.analytics.ga4_measurement_id'),
                'Google Tag Manager' => (bool) config('services.analytics.gtm_container_id'),
                'Mail delivery' => (bool) config('mail.leads_to'),
            ],
            'envWarnings' => $this->envWarnings(),
        ]);
    }

    /** @return array<int, array{label: string, detail: string}> */
    protected function envWarnings(): array
    {
        $warnings = [];

        if (blank(config('mail.leads_to'))) {
            $warnings[] = ['label' => 'MAIL_LEADS_TO', 'detail' => 'Internal inquiry notifications have no destination.'];
        }

        if (app()->environment('production') && config('app.debug')) {
            $warnings[] = ['label' => 'APP_DEBUG', 'detail' => 'Debug mode must be off in production.'];
        }

        if (app()->environment('production') && ! str_starts_with((string) config('app.url'), 'https://')) {
            $warnings[] = ['label' => 'APP_URL', 'detail' => 'Production must be served over HTTPS.'];
        }

        if (blank(config('services.analytics.ga4_measurement_id'))) {
            $warnings[] = ['label' => 'GA4_MEASUREMENT_ID', 'detail' => 'Analytics events will not reach GA4.'];
        }

        return $warnings;
    }
}
