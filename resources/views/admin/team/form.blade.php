<x-layouts.admin :title="$member->exists ? 'Edit '.$member->role : 'New profile'">
    @section('content')
        <a href="{{ route('admin.team.index') }}" class="font-mono text-[0.6875rem] uppercase tracking-[0.14em] text-mist-500 hover:text-jade-300">
            ← All profiles
        </a>

        <h1 class="mt-4 font-display text-2xl font-semibold">{{ $member->exists ? 'Edit '.$member->role : 'New team profile' }}</h1>

        <form method="POST"
              action="{{ $member->exists ? route('admin.team.update', $member) : route('admin.team.store') }}"
              class="panel mt-8 max-w-3xl space-y-5 p-6">
            @csrf
            @if ($member->exists) @method('PUT') @endif

            <div class="grid gap-5 sm:grid-cols-2">
                <x-form-field name="role" label="Role" :value="old('role', $member->role)" placeholder="Sales" />
                <x-form-field name="name" label="Name" :value="old('name', $member->name)" :required="false"
                              hint="Leave blank for role-only profiles." />
            </div>

            <x-form-field name="focus" label="Focus line" :value="old('focus', $member->focus)" :required="false"
                          placeholder="Your day-to-day contact" />
            <x-form-field name="bio" label="Bio" type="textarea" :rows="4" :value="old('bio', $member->bio)" :required="false" />
            <x-form-field name="highlights" label="Highlights" type="textarea" :rows="5" :required="false"
                          :value="old('highlights', implode("\n", $member->highlights ?? []))"
                          hint="One per line. Used for the technical specialist profile." />

            <x-form-field name="display_order" label="Display order" type="number"
                          :value="old('display_order', $member->display_order ?? 1)" :required="false" />

            <div class="space-y-3">
                <label class="flex items-center gap-3 text-sm text-mist-300">
                    <input type="checkbox" name="published" value="1" class="h-4 w-4 accent-[#96d410]"
                           @checked(old('published', $member->published ?? true))>
                    Published on the site
                </label>
                <label class="flex items-center gap-3 text-sm text-mist-300">
                    <input type="checkbox" name="is_specialist" value="1" class="h-4 w-4 accent-[#96d410]"
                           @checked(old('is_specialist', $member->is_specialist))>
                    Show as the technical specialist block
                </label>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn btn-primary">{{ $member->exists ? 'Save profile' : 'Add profile' }}</button>
                <a href="{{ route('admin.team.index') }}" class="btn btn-ghost">Cancel</a>
            </div>
        </form>
    @endsection
</x-layouts.admin>
