<x-layouts.admin :title="'Lead · '.$lead->clinic_name">
    @section('content')
        <a href="{{ route('admin.leads.index') }}" class="font-mono text-[0.6875rem] uppercase tracking-[0.14em] text-mist-500 hover:text-jade-300">
            ← All leads
        </a>

        <div class="mt-4 flex flex-wrap items-end justify-between gap-4">
            <div>
                <h1 class="font-display text-2xl font-semibold">{{ $lead->clinic_name }}</h1>
                <p class="mt-1 text-sm text-mist-500">
                    {{ $lead->fullName() }} · received {{ $lead->created_at?->format('M j, Y \a\t g:i A') }}
                </p>
            </div>
            <span class="chip chip-accent">{{ $lead->tierLabel() }}</span>
        </div>

        <div class="mt-8 grid gap-6 lg:grid-cols-[1.35fr_1fr]">
            <div class="space-y-6">
                <section class="panel p-6">
                    <h2 class="font-display text-lg font-semibold">What they told us</h2>
                    <dl class="mt-5 space-y-5 text-sm">
                        @foreach ([
                            'Biggest digital challenge' => $lead->challenge,
                            'How bookings are managed' => $lead->booking_process,
                            'How inquiries are received' => $lead->inquiry_process,
                            'Clinic management software' => $lead->software_usage,
                            'Message' => $lead->message,
                        ] as $label => $value)
                            <div>
                                <dt class="font-mono text-[0.625rem] uppercase tracking-[0.14em] text-mist-500">{{ $label }}</dt>
                                <dd class="mt-1.5 whitespace-pre-line leading-relaxed text-mist-200">{{ $value ?: '—' }}</dd>
                            </div>
                        @endforeach
                    </dl>
                </section>

                <section class="panel p-6">
                    <h2 class="font-display text-lg font-semibold">Internal notes</h2>

                    @if ($lead->notes->isEmpty())
                        <p class="mt-4 text-sm text-mist-500">No notes yet.</p>
                    @else
                        <ul class="mt-5 space-y-4">
                            @foreach ($lead->notes as $note)
                                <li class="rounded-lg border border-navy-800 bg-navy-850/50 p-4 text-sm">
                                    <p class="whitespace-pre-line text-mist-200">{{ $note->body }}</p>
                                    <p class="mt-2 font-mono text-[0.625rem] text-mist-500">
                                        {{ $note->author?->name ?? 'Unknown' }} · {{ $note->created_at?->diffForHumans(short: true) }}
                                    </p>
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    <form method="POST" action="{{ route('admin.leads.notes.store', $lead) }}" class="mt-5">
                        @csrf
                        <label class="label" for="note">Add a note</label>
                        <textarea class="field min-h-24 resize-y" id="note" name="body"
                                  placeholder="What happened on the call?" required></textarea>
                        <button type="submit" class="btn btn-primary btn-sm mt-3">Add note</button>
                    </form>
                </section>
            </div>

            <div class="space-y-6">
                <section class="panel p-6">
                    <h2 class="font-display text-lg font-semibold">Contact</h2>
                    <ul class="mt-4 space-y-3 text-sm">
                        <li>
                            <span class="block font-mono text-[0.625rem] uppercase tracking-[0.14em] text-mist-500">Email</span>
                            <a href="mailto:{{ $lead->email }}" class="break-all text-jade-300 hover:underline">{{ $lead->email }}</a>
                        </li>
                        <li>
                            <span class="block font-mono text-[0.625rem] uppercase tracking-[0.14em] text-mist-500">Phone</span>
                            <a href="tel:{{ preg_replace('/[^\d+]/', '', $lead->phone) }}" class="text-mist-200 hover:text-jade-300">{{ $lead->phone }}</a>
                        </li>
                        <li>
                            <span class="block font-mono text-[0.625rem] uppercase tracking-[0.14em] text-mist-500">City</span>
                            <span class="text-mist-200">{{ $lead->city ?: '—' }}</span>
                        </li>
                        <li>
                            <span class="block font-mono text-[0.625rem] uppercase tracking-[0.14em] text-mist-500">Current website</span>
                            <span class="break-all text-mist-200">{{ $lead->website ?: '—' }}</span>
                        </li>
                        <li>
                            <span class="block font-mono text-[0.625rem] uppercase tracking-[0.14em] text-mist-500">Prefers</span>
                            <span class="text-mist-200">{{ str($lead->preferred_contact ?? '')->headline()->toString() ?: '—' }}</span>
                        </li>
                    </ul>
                </section>

                <section class="panel p-6">
                    <h2 class="font-display text-lg font-semibold">Manage</h2>
                    <form method="POST" action="{{ route('admin.leads.update', $lead) }}" class="mt-4 space-y-4">
                        @csrf
                        @method('PATCH')

                        <div>
                            <label class="label" for="status">Status</label>
                            <select class="field" id="status" name="status">
                                @foreach ($statuses as $key => $label)
                                    <option value="{{ $key }}" @selected($lead->status === $key)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="label" for="assigned_to">Owner</label>
                            <select class="field" id="assigned_to" name="assigned_to">
                                <option value="">Unassigned</option>
                                @foreach ($team as $member)
                                    <option value="{{ $member->id }}" @selected($lead->assigned_to === $member->id)>{{ $member->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary btn-sm w-full">Save</button>
                    </form>
                </section>

                <section class="panel p-6">
                    <h2 class="font-display text-lg font-semibold">Attribution</h2>
                    <dl class="mt-4 space-y-3 text-xs">
                        @foreach ([
                            'Source page' => $lead->source_url,
                            'Campaign' => collect([$lead->utm_source, $lead->utm_medium, $lead->utm_campaign])->filter()->implode(' / '),
                            'Term / content' => collect([$lead->utm_term, $lead->utm_content])->filter()->implode(' / '),
                            'Consent recorded' => $lead->consented_at?->toDayDateTimeString(),
                        ] as $label => $value)
                            <div>
                                <dt class="font-mono text-[0.625rem] uppercase tracking-[0.14em] text-mist-500">{{ $label }}</dt>
                                <dd class="mt-1 break-all text-mist-300">{{ $value ?: '—' }}</dd>
                            </div>
                        @endforeach
                    </dl>
                </section>

                @if (auth()->user()->canManageContent())
                    <section class="rounded-xl border border-rose-ish/30 bg-rose-ish/5 p-6">
                        <h2 class="font-display text-lg font-semibold text-rose-ish">Danger zone</h2>
                        <p class="mt-2 text-sm text-mist-400">Deleting removes the inquiry and its notes permanently.</p>
                        <form method="POST" action="{{ route('admin.leads.destroy', $lead) }}" class="mt-4"
                              onsubmit="return confirm('Delete this inquiry permanently?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm border border-rose-ish/40 text-rose-ish hover:border-rose-ish">
                                Delete inquiry
                            </button>
                        </form>
                    </section>
                @endif
            </div>
        </div>
    @endsection
</x-layouts.admin>
