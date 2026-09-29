<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Admin') — {{ config('app.name') }}</title>
    <link rel="icon" href="{{ asset('img/favicon.svg') }}" type="image/svg+xml">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full bg-bone-100 text-ink-700 antialiased">
    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        <aside class="hidden w-64 shrink-0 flex-col border-r border-ink-900/10 bg-bone-50 lg:flex">
            <div class="flex h-16 items-center gap-2.5 border-b border-ink-900/10 px-5">
                <a href="{{ route('admin.dashboard') }}" class="text-ink-900">
                    <x-logo class="h-6 w-auto" />
                </a>
                <span class="meta ml-auto text-ink-400">Admin</span>
            </div>

            <nav class="flex-1 space-y-0.5 overflow-y-auto p-3" aria-label="Admin">
                @foreach ([
                    ['Dashboard', 'admin.dashboard', 'admin.dashboard'],
                    ['Leads', 'admin.leads.index', 'admin.leads.*'],
                    ['Services', 'admin.services.index', 'admin.services.*'],
                    ['Projects', 'admin.projects.index', 'admin.projects.*'],
                    ['FAQs', 'admin.faqs.index', 'admin.faqs.*'],
                    ['Team', 'admin.team.index', 'admin.team.*'],
                ] as [$label, $route, $pattern])
                    @continue(auth()->user()->role === \App\Models\User::ROLE_VIEWER && ! in_array($route, ['admin.dashboard', 'admin.leads.index'], true))
                    <a href="{{ route($route) }}"
                       @class([
                           'flex items-center rounded-md px-3 py-2.5 text-sm transition-colors',
                           'bg-ink-900 text-bone-50' => request()->routeIs($pattern),
                           'text-ink-600 hover:bg-bone-200 hover:text-ink-900' => ! request()->routeIs($pattern),
                       ])>
                        {{ $label }}
                    </a>
                @endforeach

                @if (auth()->user()->isAdmin())
                    <p class="meta px-3 pb-2 pt-7 text-ink-300">Configuration</p>

                    @foreach ([
                        ['Settings', 'admin.settings.edit', 'admin.settings.*'],
                        ['Audit log', 'admin.audit-log', 'admin.audit-log'],
                    ] as [$label, $route, $pattern])
                        <a href="{{ route($route) }}"
                           @class([
                               'flex items-center rounded-md px-3 py-2.5 text-sm transition-colors',
                               'bg-ink-900 text-bone-50' => request()->routeIs($pattern),
                               'text-ink-600 hover:bg-bone-200 hover:text-ink-900' => ! request()->routeIs($pattern),
                           ])>
                            {{ $label }}
                        </a>
                    @endforeach
                @endif
            </nav>

            <div class="border-t border-ink-900/10 p-4">
                <p class="truncate text-sm font-medium text-ink-900">{{ auth()->user()->name }}</p>
                <p class="meta mt-0.5 text-ink-400">{{ auth()->user()->roleLabel() }}</p>

                <div class="mt-4 flex gap-2">
                    <a href="{{ route('home') }}" target="_blank" rel="noopener" class="btn btn-outline btn-sm flex-1">View site</a>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline btn-sm">Sign out</button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- Content --}}
        <div class="flex min-w-0 flex-1 flex-col">
            <header class="flex items-center gap-4 border-b border-ink-900/10 bg-bone-50 px-5 py-3.5 lg:hidden">
                <x-logo-mark class="h-6 w-6 text-ink-900" />
                <span class="font-display text-sm font-semibold text-ink-900">Admin</span>
                <div class="ml-auto flex gap-2">
                    <a href="{{ route('home') }}" target="_blank" rel="noopener" class="btn btn-outline btn-sm">Site</a>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline btn-sm">Out</button>
                    </form>
                </div>
            </header>

            <main class="flex-1 p-5 md:p-8">
                @if (session('status'))
                    <div role="status" class="mb-6 rounded-lg border border-jade-500/30 bg-jade-300/20 px-4 py-3 text-sm text-jade-700">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div role="alert" class="mb-6 rounded-lg border border-brick-400/40 bg-brick-400/10 px-4 py-3 text-sm text-brick-500">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
