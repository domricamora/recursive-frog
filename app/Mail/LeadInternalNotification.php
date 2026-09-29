<?php

namespace App\Mail;

use App\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/** Internal notification for the Recursive Frog team (plan.md #36). */
class LeadInternalNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Lead $lead) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Recursive Frog clinic inquiry — '.$this->lead->clinic_name,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.lead-internal',
            with: ['lead' => $this->lead],
        );
    }
}
