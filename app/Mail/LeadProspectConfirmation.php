<?php

namespace App\Mail;

use App\Models\Lead;
use App\Support\Site;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Prospect confirmation (plan.md #36). Confirms receipt without promising
 * a response time beyond the documented one-business-day commitment.
 */
class LeadProspectConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Lead $lead) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'We\'ve received your Recursive Frog inquiry');
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.lead-confirmation',
            with: [
                'lead' => $this->lead,
                'company' => Site::name(),
            ],
        );
    }
}
