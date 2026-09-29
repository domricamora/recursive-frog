<x-layouts.admin title="Leads">
    @section('content')
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <h1 class="font-display text-2xl font-semibold">Leads</h1>
                <p class="mt-1 text-sm text-mist-500">{{ $leads->total() }} {{ Str::plural('inquiry', $leads->total()) }}</p>
            </div>
            <a href="{{ route('admin.leads.export', $filters) }}" class="btn btn-ghost btn-sm">Export CSV</a>
        </div>

        <form method="GET" class="panel mt-6 flex flex-wrap items-end gap-4 p-5">
            <div class="min-w-48 flex-1">
                <label class="label" for="q">Search</label>
                <input class="field" type="search" id="q" name="q" value="{{ $filters['q'] ?? '' }}"
                       placeholder="Name, clinic, email or phone">
            </div>

            <div>
                <label class="label" for="status">Status</label>
                <select class="field" id="status" name="status">
                    <option value="">All statuses</option>
                    @foreach ($statuses as $key => $label)
                        <option value="{{ $key }}" @selected(($filters['status'] ?? '') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="label" for="tier">Tier</label>
                <select class="field" id="tier" name="tier">
                    <option value="">All tiers</option>
                    <option value="not_sure" @selected(($filters['tier'] ?? '') === 'not_sure')>Not sure</option>
                    @foreach (\App\Models\ServiceTier::orderBy('display_order')->get() as $tier)
                        <option value="{{ $tier->slug }}" @selected(($filters['tier'] ?? '') === $tier->slug)>{{ $tier->name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary btn-sm">Filter</button>

            @if (array_filter($filters))
                <a href="{{ route('admin.leads.index') }}" class="btn btn-ghost btn-sm">Reset</a>
            @endif
        </form>

        <div class="panel mt-6 overflow-hidden">
            @if ($leads->isEmpty())
                <p class="p-8 text-sm text-mist-500">No leads match these filters.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[52rem] text-left text-sm">
                        <thead class="border-b border-navy-800 bg-navy-850/60 font-mono text-[0.625rem] uppercase tracking-[0.14em] text-mist-500">
                            <tr>
                                <th scope="col" class="px-5 py-3 font-normal">Clinic</th>
                                <th scope="col" class="px-5 py-3 font-normal">Contact</th>
                                <th scope="col" class="px-5 py-3 font-normal">Tier</th>
                                <th scope="col" class="px-5 py-3 font-normal">Status</th>
                                <th scope="col" class="px-5 py-3 font-normal">Owner</th>
                                <th scope="col" class="px-5 py-3 font-normal">Received</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-navy-800">
                            @foreach ($leads as $lead)
                                <tr class="transition-colors hover:bg-navy-850/50">
                                    <td class="px-5 py-4">
                                        <a href="{{ route('admin.leads.show', $lead) }}" class="font-medium text-mist-50 hover:text-jade-300">
                                            {{ $lead->clinic_name }}
                                        </a>
                                        <span class="block font-mono text-[0.625rem] text-mist-500">
                                            RF-{{ str_pad((string) $lead->id, 5, '0', STR_PAD_LEFT) }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4">
                                        <span class="block text-mist-200">{{ $lead->fullName() }}</span>
                                        <a href="mailto:{{ $lead->email }}" class="block text-xs text-mist-500 hover:text-jade-300">{{ $lead->email }}</a>
                                    </td>
                                    <td class="px-5 py-4 text-mist-300">{{ $lead->tierLabel() }}</td>
                                    <td class="px-5 py-4"><span class="chip">{{ $lead->statusLabel() }}</span></td>
                                    <td class="px-5 py-4 text-mist-400">{{ $lead->assignee?->name ?? '—' }}</td>
                                    <td class="px-5 py-4 font-mono text-xs text-mist-500">
                                        {{ $lead->created_at?->format('M j, Y') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-navy-800 px-5 py-4">
                    {{ $leads->links() }}
                </div>
            @endif
        </div>
    @endsection
</x-layouts.admin>
