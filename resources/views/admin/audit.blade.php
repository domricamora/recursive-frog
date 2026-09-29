<x-layouts.admin title="Audit log">
    @section('content')
        <h1 class="font-display text-2xl font-semibold">Audit log</h1>
        <p class="mt-1 max-w-2xl text-sm text-mist-500">
            Sensitive admin actions are recorded here — sign-ins, lead access, content changes and deletions.
        </p>

        <div class="panel mt-8 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[44rem] text-left text-sm">
                    <thead class="border-b border-navy-800 bg-navy-850/60 font-mono text-[0.625rem] uppercase tracking-[0.14em] text-mist-500">
                        <tr>
                            <th scope="col" class="px-5 py-3 font-normal">When</th>
                            <th scope="col" class="px-5 py-3 font-normal">Who</th>
                            <th scope="col" class="px-5 py-3 font-normal">Action</th>
                            <th scope="col" class="px-5 py-3 font-normal">Detail</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-navy-800">
                        @forelse ($logs as $log)
                            <tr>
                                <td class="whitespace-nowrap px-5 py-3 font-mono text-xs text-mist-500">
                                    {{ $log->created_at?->format('M j, Y H:i') }}
                                </td>
                                <td class="px-5 py-3 text-mist-300">{{ $log->user?->name ?? 'system' }}</td>
                                <td class="px-5 py-3"><span class="chip">{{ $log->action }}</span></td>
                                <td class="px-5 py-3 text-mist-400">{{ $log->description }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-5 py-8 text-mist-500">Nothing logged yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($logs->hasPages())
                <div class="border-t border-navy-800 px-5 py-4">{{ $logs->links() }}</div>
            @endif
        </div>
    @endsection
</x-layouts.admin>
