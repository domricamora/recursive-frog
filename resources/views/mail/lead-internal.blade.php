<x-mail::message>
# New business inquiry

A new lead was submitted through the Recursive Frog website.

**Contact:** {{ $lead->fullName() }}  
**Business:** {{ $lead->clinic_name }}  
**Industry:** {{ $lead->industry ?: '—' }}  
**Email:** [{{ $lead->email }}](mailto:{{ $lead->email }})  
**Phone:** {{ $lead->phone }}  
**City:** {{ $lead->city ?: '—' }}  
**Current website:** {{ $lead->website ?: '—' }}  
**Preferred contact:** {{ $lead->preferred_contact ?: '—' }}

**Selected tier:** {{ $lead->tierLabel() }}

## Business details

- **Biggest digital challenge:** {{ $lead->challenge ?: '—' }}
- **How bookings are managed:** {{ $lead->booking_process ?: '—' }}
- **How inquiries are received:** {{ $lead->inquiry_process ?: '—' }}
- **Business software and tools:** {{ $lead->software_usage ?: '—' }}

## Message

{{ $lead->message ?: '—' }}

---

**Source page:** {{ $lead->source_url ?: '—' }}  
**UTM:** {{ collect([$lead->utm_source, $lead->utm_medium, $lead->utm_campaign, $lead->utm_term, $lead->utm_content])->filter()->implode(' / ') ?: '—' }}  
**Consent recorded:** {{ $lead->consented_at?->toDayDateTimeString() }}  
**Lead ID:** #{{ $lead->id }}

@component('mail::button', ['url' => route('admin.leads.show', $lead)])
Review this lead
@endcomponent

Thanks,  
{{ config('app.name') }}
</x-mail::message>
