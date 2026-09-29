<?php

namespace App\Support;

use App\Models\Lead;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Optional HubSpot CRM sync (plan.md #23, #28). Disabled unless a private
 * app token is configured. Failures are logged and never block a lead.
 */
class HubSpot
{
    public static function enabled(): bool
    {
        return filled(config('services.hubspot.access_token'));
    }

    /** @return string|null HubSpot contact id on success */
    public static function pushContact(Lead $lead): ?string
    {
        if (! static::enabled()) {
            return null;
        }

        $properties = array_filter([
            'firstname' => $lead->first_name,
            'lastname' => $lead->last_name,
            'email' => $lead->email,
            'phone' => $lead->phone,
            'company' => $lead->clinic_name,
            'city' => $lead->city,
            'website' => $lead->website,
            'hs_lead_status' => 'NEW',
        ], fn ($value) => filled($value));

        $base = 'https://api.hubapi.com/crm/v3/objects/contacts';

        try {
            $response = $lead->hubspot_contact_id
                ? static::client()->patch($base.'/'.$lead->hubspot_contact_id, ['properties' => $properties])
                : static::client()->post($base, ['properties' => $properties]);

            if ($response->failed()) {
                throw new \RuntimeException($response->status().': '.$response->body());
            }

            return $response->json('id');
        } catch (\Throwable $e) {
            Log::warning('HubSpot contact sync failed.', ['message' => $e->getMessage(), 'lead_id' => $lead->id]);

            return null;
        }
    }

    /** Human-readable summary of the inquiry for the CRM activity timeline. */
    public static function noteBody(Lead $lead): string
    {
        return collect([
            'Tier interest: '.$lead->tierLabel(),
            'Biggest challenge: '.($lead->challenge ?: '—'),
            'Bookings: '.($lead->booking_process ?: '—'),
            'Inquiries: '.($lead->inquiry_process ?: '—'),
            'Clinic software: '.($lead->software_usage ?: '—'),
            'Message: '.($lead->message ?: '—'),
        ])->implode(PHP_EOL);
    }

    protected static function client(): PendingRequest
    {
        return Http::withToken((string) config('services.hubspot.access_token'))
            ->acceptJson()
            ->timeout(8);
    }
}
