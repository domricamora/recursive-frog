<x-mail::message>
# Thanks — we have your message

Hello {{ $lead->first_name }},

Thanks for reaching out about {{ $lead->clinic_name }}. Your inquiry reached our team and we have it in front of us.

We will review what you sent over and get back to you. Questions are answered within one business day.

A short summary of what you shared:

- **Interest:** {{ $lead->tierLabel() }}
- **Current website:** {{ $lead->website ?: '—' }}
- **City:** {{ $lead->city ?: '—' }}

While you wait, it may be useful to look at how we work: [how the process runs]({{ route('how-it-works') }}) and [systems we have already built]({{ route('work.index') }}).

Talk soon,  
The {{ $company }} team

@component('mail::button', ['url' => route('work.index')])
Explore our work
@endcomponent
</x-mail::message>
