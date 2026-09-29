<x-layouts.admin title="FAQs">
    @section('content')
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <h1 class="font-display text-2xl font-semibold">FAQs</h1>
                <p class="mt-1 text-sm text-mist-500">Published answers appear on the FAQ page and in its structured data.</p>
            </div>
            <a href="{{ route('admin.faqs.create') }}" class="btn btn-primary btn-sm">New question</a>
        </div>

        <div class="panel mt-8 overflow-hidden">
            @if ($faqs->isEmpty())
                <p class="p-8 text-sm text-mist-500">No questions yet.</p>
            @else
                <ul class="divide-y divide-navy-800">
                    @foreach ($faqs as $faq)
                        <li class="flex flex-wrap items-start gap-4 p-5 transition-colors hover:bg-navy-850/40">
                            <div class="min-w-0 flex-1">
                                <p class="font-medium text-mist-50">{{ $faq->question }}</p>
                                <p class="mt-1 line-clamp-2 text-sm text-mist-500">{{ strip_tags($faq->answer) }}</p>
                                <p class="mt-2 flex flex-wrap items-center gap-2">
                                    <span @class(['chip', 'chip-accent' => $faq->published])>{{ $faq->published ? 'Published' : 'Draft' }}</span>
                                    @if ($faq->category)
                                        <span class="chip">{{ $faq->category }}</span>
                                    @endif
                                    <span class="font-mono text-[0.625rem] text-mist-500">order {{ $faq->display_order }}</span>
                                </p>
                            </div>

                            <div class="flex gap-2">
                                <a href="{{ route('admin.faqs.edit', $faq) }}" class="btn btn-ghost btn-sm">Edit</a>
                                <form method="POST" action="{{ route('admin.faqs.destroy', $faq) }}"
                                      onsubmit="return confirm('Delete this question?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm border border-rose-ish/40 text-rose-ish hover:border-rose-ish">Delete</button>
                                </form>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    @endsection
</x-layouts.admin>
