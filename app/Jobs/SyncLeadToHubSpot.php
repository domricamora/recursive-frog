<?php

namespace App\Jobs;

use App\Models\Lead;
use App\Support\HubSpot;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

/** Optional CRM sync, run off the request lifecycle (plan.md #23). */
class SyncLeadToHubSpot implements ShouldQueue
{
    use Queueable;

    public int $tries = 2;

    public function __construct(public Lead $lead) {}

    public function handle(): void
    {
        $id = HubSpot::pushContact($this->lead);

        if ($id && $this->lead->hubspot_contact_id !== $id) {
            $this->lead->forceFill(['hubspot_contact_id' => $id])->saveQuietly();
        }
    }
}
