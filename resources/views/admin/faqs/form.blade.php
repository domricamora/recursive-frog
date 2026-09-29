<x-layouts.admin :title="$faq->exists ? 'Edit question' : 'New question'">
    @section('content')
        <a href="{{ route('admin.faqs.index') }}" class="font-mono text-[0.6875rem] uppercase tracking-[0.14em] text-mist-500 hover:text-jade-300">
            ← All questions
        </a>

        <h1 class="mt-4 font-display text-2xl font-semibold">{{ $faq->exists ? 'Edit question' : 'New question' }}</h1>

        <form method="POST"
              action="{{ $faq->exists ? route('admin.faqs.update', $faq) : route('admin.faqs.store') }}"
              class="panel mt-8 max-w-3xl space-y-5 p-6">
            @csrf
            @if ($faq->exists) @method('PUT') @endif

            <x-form-field name="question" label="Question" :value="old('question', $faq->question)" placeholder="What does Recursive Frog build?" />
            <x-form-field name="answer" label="Answer" type="textarea" :rows="6" :value="old('answer', $faq->answer)" />
            <x-form-field name="category" label="Category" :value="old('category', $faq->category)" :required="false"
                          placeholder="Services, Ownership, Responsible AI…" />
            <x-form-field name="display_order" label="Display order" type="number"
                          :value="old('display_order', $faq->display_order ?? 1)" :required="false" />

            <label class="flex items-center gap-3 text-sm text-mist-300">
                <input type="checkbox" name="published" value="1" class="h-4 w-4 accent-[#96d410]"
                       @checked(old('published', $faq->published ?? true))>
                Published on the site
            </label>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn btn-primary">{{ $faq->exists ? 'Save question' : 'Add question' }}</button>
                <a href="{{ route('admin.faqs.index') }}" class="btn btn-ghost">Cancel</a>
            </div>
        </form>
    @endsection
</x-layouts.admin>
