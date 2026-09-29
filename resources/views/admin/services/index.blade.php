<x-layouts.admin title="Services">
    @section('content')
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <h1 class="font-display text-2xl font-semibold">Service tiers</h1>
                <p class="mt-1 max-w-2xl text-sm text-mist-500">
                    Package inclusions are still being finalised. Features marked <span class="chip">Draft</span>
                    are managed here but hidden from the public site until confirmed.
                </p>
            </div>
            <a href="{{ route('admin.services.create') }}" class="btn btn-primary btn-sm">New tier</a>
        </div>

        <div class="mt-8 space-y-5">
            @forelse ($tiers as $tier)
                <article class="panel p-6">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <div class="flex flex-wrap items-center gap-2">
                                <h2 class="font-display text-xl font-semibold">{{ $tier->name }}</h2>
                                <span class="chip">{{ $tier->model }}</span>
                                <span @class([
                                    'chip chip-accent' => $tier->is_active,
                                    'chip' => ! $tier->is_active,
                                ])>{{ $tier->is_active ? 'Visible' : 'Hidden' }}</span>
                            </div>
                            <p class="mt-2 text-sm text-mist-400">{{ $tier->includes }}</p>
                            <p class="mt-1 break-all font-mono text-[0.625rem] text-mist-500">/{{ $tier->slug }}</p>
                        </div>

                        <div class="flex gap-2">
                            <a href="{{ route('services.show', $tier) }}" target="_blank" rel="noopener" class="btn btn-ghost btn-sm">View</a>
                            <a href="{{ route('admin.services.edit', $tier) }}" class="btn btn-ghost btn-sm">Edit</a>
                            @if (auth()->user()->canManageContent())
                                <form method="POST" action="{{ route('admin.services.destroy', $tier) }}"
                                      onsubmit="return confirm('Delete {{ $tier->name }} and all its features?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm border border-rose-ish/40 text-rose-ish hover:border-rose-ish">Delete</button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <ul class="mt-5 flex flex-wrap gap-2">
                        @forelse ($tier->features as $feature)
                            <li @class([
                                'chip normal-case tracking-normal',
                                'chip-accent' => $feature->status === \App\Models\ServiceTier::STATUS_CONFIRMED,
                            ])>
                                {{ $feature->name }}
                                <span class="ml-1 font-mono text-[0.5625rem] uppercase text-mist-500">{{ $feature->statusLabel() }}</span>
                            </li>
                        @empty
                            <li class="text-sm text-mist-500">No features yet.</li>
                        @endforelse
                    </ul>
                </article>
            @empty
                <p class="panel p-8 text-sm text-mist-500">No tiers yet.</p>
            @endforelse
        </div>
    @endsection
</x-layouts.admin>
