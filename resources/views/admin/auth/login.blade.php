<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Sign in — {{ config('app.name') }}</title>
    <link rel="icon" href="{{ asset('img/favicon.svg') }}" type="image/svg+xml">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full bg-bone-100 text-ink-700 antialiased">
    <div class="rf-grid fixed inset-0 opacity-60" aria-hidden="true"></div>

    <main class="relative flex min-h-screen items-center justify-center p-6">
        <div class="w-full max-w-sm">
            <div class="text-center">
                <h1 class="mx-auto w-fit text-ink-900">
                    <x-logo class="h-9 w-auto" label="Recursive Frog" />
                </h1>
                <p class="meta mt-2 text-ink-400">Admin sign in</p>
            </div>

            <form method="POST" action="{{ route('admin.login.store') }}" class="card mt-8 space-y-5 p-7">
                @csrf

                <div>
                    <label class="label" for="email">Email</label>
                    <input class="field" type="email" id="email" name="email" value="{{ old('email') }}"
                           autocomplete="username" required autofocus
                           @if ($errors->has('email')) aria-invalid="true" aria-describedby="email-error" @endif>
                    @error('email') <span class="error" id="email-error">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="label" for="password">Password</label>
                    <input class="field" type="password" id="password" name="password"
                           autocomplete="current-password" required>
                </div>

                <label class="flex items-center gap-2.5 text-sm text-ink-600">
                    <input type="checkbox" name="remember" value="1" class="check">
                    Keep me signed in
                </label>

                <button type="submit" class="btn btn-primary w-full">Sign in</button>
            </form>

            <p class="mt-6 text-center text-xs leading-relaxed text-ink-400">
                Protected area. Access is logged.<br>
                <a href="{{ route('home') }}" class="link-rule text-ink-600">← Back to the website</a>
            </p>
        </div>
    </main>
</body>

</html>
