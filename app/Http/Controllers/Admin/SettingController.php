<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\SiteSetting;
use App\Support\Site;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

/** Contact details, social links, SEO metadata and audit log (plan.md #34). */
class SettingController extends Controller
{
    public function edit(): View
    {
        return view('admin.settings', [
            'settings' => SiteSetting::query()->orderBy('display_order')->orderBy('id')->get(),
            'integrationStatus' => [
                'HubSpot CRM' => (bool) config('services.hubspot.access_token'),
                'GA4' => (bool) config('services.analytics.ga4_measurement_id'),
                'Google Tag Manager' => (bool) config('services.analytics.gtm_container_id'),
                'Mail delivery' => (bool) config('mail.leads_to'),
            ],
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $values = $request->validate([
            'settings' => ['required', 'array'],
            'settings.*' => ['nullable', 'string', 'max:5000'],
        ]);

        foreach ($values['settings'] as $key => $value) {
            SiteSetting::put($key, $value);
        }

        Cache::forget('site-settings.all');
        Site::flush();

        AuditLog::record('settings.updated', null, 'Updated site settings: '.implode(', ', array_keys($values['settings'])));

        return back()->with('status', 'Settings saved.');
    }

    public function auditLog(): View
    {
        return view('admin.audit', [
            'logs' => AuditLog::query()->with('user')->latest()->paginate(40),
        ]);
    }
}
