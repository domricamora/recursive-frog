<?php

namespace App\Http\Controllers;

use App\Jobs\SyncLeadToHubSpot;
use App\Mail\LeadInternalNotification;
use App\Mail\LeadProspectConfirmation;
use App\Models\Lead;
use App\Models\ServiceTier;
use App\Http\Requests\StoreLeadRequest;
use App\Support\HubSpot;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

/** Find Your Tier flow: form, lead capture, notifications (plan.md #22–#24). */
class ContactController extends Controller
{
    public function create(): View
    {
        return $this->publicView('contact', [
            'title' => 'Find Your Tier',
            'description' => 'Tell us a little about your clinic and how you currently handle bookings, inquiries and administration.',
        ], [
            'tiers' => ServiceTier::query()->active()->ordered()->get(),
            'preferredContacts' => [
                'email' => 'Email',
                'phone' => 'Phone call',
                'messenger' => 'Messenger',
                'viber' => 'Viber',
                'whatsapp' => 'WhatsApp',
            ],
        ]);
    }

    public function store(StoreLeadRequest $request): RedirectResponse
    {
        $lead = Lead::create([
            ...$request->safe()->except(['consent', 'source_url']),
            'source_url' => $request->input('source_url') ?: $request->headers->get('referer'),
            'tier_label' => $request->tierLabel(),
            'consent' => true,
            'consented_at' => now(),
            'status' => Lead::STATUS_NEW,
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 1000),
        ]);

        // Notifications must never block a captured lead (plan.md #23).
        $this->notifyInternal($lead);
        $this->notifyProspect($lead);

        if (HubSpot::enabled()) {
            SyncLeadToHubSpot::dispatch($lead);
        }

        return redirect()
            ->route('contact.thank-you')
            ->with('lead_reference', 'RF-'.str_pad((string) $lead->id, 5, '0', STR_PAD_LEFT));
    }

    public function thankYou(): View
    {
        return $this->publicView('contact.thank-you', [
            'title' => 'Thanks',
            'description' => 'We have received your message and will review the information you provided.',
        ]);
    }

    protected function notifyInternal(Lead $lead): void
    {
        try {
            Mail::to(config('mail.leads_to'))->send(new LeadInternalNotification($lead));
        } catch (\Throwable $e) {
            Log::error('Internal lead notification failed.', ['message' => $e->getMessage(), 'lead_id' => $lead->id]);
        }
    }

    protected function notifyProspect(Lead $lead): void
    {
        try {
            Mail::to($lead->email)->send(new LeadProspectConfirmation($lead));
        } catch (\Throwable $e) {
            Log::error('Prospect confirmation failed.', ['message' => $e->getMessage(), 'lead_id' => $lead->id]);
        }
    }
}
