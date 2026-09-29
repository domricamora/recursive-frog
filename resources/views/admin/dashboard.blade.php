<x-layouts.admin title="Dashboard">
    @section('content')
        <div class="mb-8">
            <h1 class="font-display text-2xl font-semibold">Dashboard</h1>
            <p class="mt-1 text-sm text-mist-500">Inquiries, content and system status at a glance.</p>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ([
                ['Total leads', $stats['totalLeads'], route('admin.leads.index'), 'all time'],
                ['New leads', $stats['newLeads'], route('admin.leads.index', ['status' => 'new']), 'needs a first reply'],
                ['This month', $stats['thisMonth'], route('admin.leads.index'), 'received'],
                ['In pipeline', $stats['qualified'], route('admin.leads.index', ['status' => 'qualified']), 'qualified → won'],
            ] as [$label, $value, $link, $note])
                <a href="{{ $link }}" class="panel block p-5 transition-colors hover:border-navy-600">
                    <p class="font-mono text-[0.625rem] uppercase tracking-[0.14em] text-mist-500">{{ $label }}</p>
                    <p class="mt-3 font-display text-3xl font-semibold text-mist-50 tnum">{{ $value }}</p>
                    <p class="mt-1 text-xs text-mist-500">{{ $note }}</p>
                </a>
            @endforeach
        </div>

        @if ($envWarnings)
            <div class="mt-6 rounded-xl border border-amber-ish/30 bg-amber-ish/10 p-5">
                <p class="font-display font-semibold text-amber-ish">Before launch</p>
                <ul class="mt-2 space-y-1 text-sm text-mist-300">
                    @foreach ($envWarnings as $warning)
                        <li><span class="font-mono text-xs text-amber-ish">{{ $warning['label'] }}</span> — {{ $warning['detail'] }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mt-8 grid gap-6 lg:grid-cols-[1.4fr_1fr]">
            <section class="panel p-6">
                <div class="flex items-center justify-between">
                    <h2 class="font-display text-lg font-semibold">Recent leads</h2>
                    <a href="{{ route('admin.leads.index') }}" class="font-mono text-[0.6875rem] uppercase tracking-[0.14em] text-jade-400 hover:text-jade-300">All leads →</a>
                </div>

                @if ($recentLeads->isEmpty())
                    <p class="mt-6 text-sm text-mist-500">No leads yet. They appear here the moment the contact form is used.</p>
                @else
                    <ul class="mt-5 divide-y divide-navy-800">
                        @foreach ($recentLeads as $lead)
                            <li>
                                <a href="{{ route('admin.leads.show', $lead) }}" class="flex items-center gap-4 py-3 transition-colors hover:bg-navy-850/50">
                                    <span class="min-w-0 flex-1">
                                        <span class="block truncate text-sm font-medium text-mist-50">{{ $lead->clinic_name }}</span>
                                        <span class="block truncate text-xs text-mist-500">{{ $lead->fullName() }} · {{ $lead->tierLabel() }}</span>
                                    </span>
                                    <span class="chip shrink-0">{{ $lead->statusLabel() }}</span>
                                    <span class="hidden shrink-0 font-mono text-[0.625rem] text-mist-500 sm:block">{{ $lead->created_at?->diffForHumans(short: true) }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>

            <div class="space-y-6">
                <section class="panel p-6">
                    <h2 class="font-display text-lg font-semibold">Pipeline</h2>
                    <ul class="mt-5 space-y-3">
                        @foreach ($statusCounts as $row)
                            <li>
                                <a href="{{ route('admin.leads.index', ['status' => $row['status']]) }}" class="flex items-center gap-3 text-sm">
                                    <span class="w-24 shrink-0 text-mist-400">{{ $row['label'] }}</span>
                                    <span class="h-1.5 flex-1 overflow-hidden rounded-full bg-navy-800">
                                        <span class="block h-full rounded-full bg-jade-500/70"
                                              style="width: {{ $stats['totalLeads'] ? max(3, round($row['count'] / $stats['totalLeads'] * 100)) : 0 }}%"></span>
                                    </span>
                                    <span class="w-6 shrink-0 text-right font-mono text-xs text-mist-500 tnum">{{ $row['count'] }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </section>

                <section class="panel p-6">
                    <h2 class="font-display text-lg font-semibold">Tier interest</h2>
                    @if ($tierInterest->isEmpty())
                        <p class="mt-4 text-sm text-mist-500">No data yet.</p>
                    @else
                        <ul class="mt-5 space-y-2 text-sm">
                            @foreach ($tierInterest as $row)
                                <li class="flex items-center justify-between gap-3">
                                    <span class="truncate text-mist-300">{{ $row->tier_label ?: 'Not sure yet' }}</span>
                                    <span class="font-mono text-xs text-mist-500 tnum">{{ $row->total }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </section>

                <section class="panel p-6">
                    <h2 class="font-display text-lg font-semibold">Content</h2>
                    <ul class="mt-4 grid grid-cols-2 gap-3 text-sm">
                        @foreach ([
                            ['Tiers', $stats['tiers'], 'admin.services.index'],
                            ['Projects', $stats['projects'], 'admin.projects.index'],
                            ['FAQs', $stats['faqs'], 'admin.faqs.index'],
                            ['Team', $stats['team'], 'admin.team.index'],
                        ] as [$label, $value, $link])
                            <li>
                                <a href="{{ route($link) }}" class="block rounded-lg border border-navy-800 p-3 transition-colors hover:border-navy-600">
                                    <span class="block font-mono text-[0.625rem] uppercase tracking-[0.14em] text-mist-500">{{ $label }}</span>
                                    <span class="mt-1 block font-display text-xl font-semibold text-jade-300 tnum">{{ $value }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>

                    <ul class="mt-5 space-y-2 border-t border-navy-800 pt-4 text-xs">
                        @foreach ($integrationStatus as $name => $enabled)
                            <li class="flex items-center gap-2">
                                <span class="h-1.5 w-1.5 rounded-full {{ $enabled ? 'bg-jade-400' : 'bg-navy-600' }}" aria-hidden="true"></span>
                                <span class="{{ $enabled ? 'text-mist-300' : 'text-mist-500' }}">{{ $name }}</span>
                                <span class="ml-auto font-mono text-[0.625rem] uppercase tracking-[0.12em] {{ $enabled ? 'text-jade-400' : 'text-navy-500' }}">
                                    {{ $enabled ? 'connected' : 'off' }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </section>
            </div>
        </div>

        <section class="panel mt-6 p-6">
            <h2 class="font-display text-lg font-semibold">Recent admin activity</h2>
            @if ($auditLogs->isEmpty())
                <p class="mt-4 text-sm text-mist-500">Nothing logged yet.</p>
            @else
                <ul class="mt-4 divide-y divide-navy-800 text-sm">
                    @foreach ($auditLogs as $log)
                        <li class="flex flex-wrap items-center gap-x-3 gap-y-1 py-2.5">
                            <span class="font-mono text-[0.625rem] text-jade-400/80">{{ $log->action }}</span>
                            <span class="text-mist-300">{{ $log->description }}</span>
                            <span class="ml-auto font-mono text-[0.625rem] text-mist-500">
                                {{ $log->user?->name ?? 'system' }} · {{ $log->created_at?->diffForHumans(short: true) }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>
    @endsection
</x-layouts.admin>
