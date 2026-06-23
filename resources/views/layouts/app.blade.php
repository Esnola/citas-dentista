<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    @fluxAppearance
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    @livewireStyles
</head>
<body class="min-h-screen bg-slate-950 text-slate-100 antialiased">
    <div class="relative min-h-screen">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,rgba(16,185,129,0.18),transparent_35%),linear-gradient(180deg,#020617,#0f172a)]"></div>
        <div class="relative mx-auto flex min-h-screen max-w-7xl">
            <aside class="sticky top-0 hidden h-screen w-72 shrink-0 border-r border-white/10 bg-slate-950/70 px-4 py-5 shadow-[18px_0_60px_rgba(15,23,42,0.32)] backdrop-blur-xl xl:block">
                <nav class="flex h-full min-h-0 flex-col gap-5 text-sm">
                    <a href="{{ route('dashboard') }}" class="group flex items-center gap-3 rounded-3xl border border-emerald-400/20 bg-emerald-400/10 p-3 text-emerald-100 transition-colors hover:bg-emerald-400/15">
                        <span class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl border border-emerald-300/25 bg-emerald-300/15 text-sm font-bold text-emerald-200 shadow-[0_10px_30px_rgba(16,185,129,0.14)]">WA</span>
                        <span class="min-w-0">
                            <span class="block truncate text-base font-semibold">{{ config('app.name', 'Laravel') }}</span>
                            <span class="mt-0.5 block text-xs uppercase tracking-[0.22em] text-emerald-300/75">Scheduler</span>
                        </span>
                    </a>

                    <div class="min-h-0 flex-1 overflow-y-auto pr-1">
                        <div class="grid gap-2">
                            <a
                                @class([
                                    'group flex items-center gap-3 rounded-full border px-3 py-2 font-medium transition-colors',
                                    'border-sky-400/25 bg-sky-400/15 text-sky-200' => request()->routeIs('dashboard'),
                                    'border-white/10 bg-white/5 text-slate-300 hover:border-sky-400/20 hover:bg-sky-400/10 hover:text-sky-200' => ! request()->routeIs('dashboard'),
                                ])
                                href="{{ route('dashboard') }}"
                            >
                                <span class="size-2 rounded-full bg-sky-300"></span>
                                Dashboard
                            </a>
                            <a
                                @class([
                                    'group flex items-center gap-3 rounded-full border px-3 py-2 font-medium transition-colors',
                                    'border-emerald-400/25 bg-emerald-400/15 text-emerald-200' => request()->routeIs('clients.*'),
                                    'border-white/10 bg-white/5 text-slate-300 hover:border-emerald-400/20 hover:bg-emerald-400/10 hover:text-emerald-200' => ! request()->routeIs('clients.*'),
                                ])
                                href="{{ route('clients.index') }}"
                            >
                                <span class="size-2 rounded-full bg-emerald-300"></span>
                                Clientes
                            </a>
                            <a
                                @class([
                                    'group flex items-center gap-3 rounded-full border px-3 py-2 font-medium transition-colors',
                                    'border-yellow-400/25 bg-yellow-400/15 text-yellow-200' => request()->routeIs('appointments.*'),
                                    'border-white/10 bg-white/5 text-slate-300 hover:border-yellow-400/20 hover:bg-yellow-400/10 hover:text-yellow-200' => ! request()->routeIs('appointments.*'),
                                ])
                                href="{{ route('appointments.index') }}"
                            >
                                <span class="size-2 rounded-full bg-yellow-300"></span>
                                Citas
                            </a>
                            <a
                                @class([
                                    'group flex items-center gap-3 rounded-full border px-3 py-2 font-medium transition-colors',
                                    'border-violet-400/25 bg-violet-400/15 text-violet-200' => request()->routeIs('imports.*'),
                                    'border-white/10 bg-white/5 text-slate-300 hover:border-violet-400/20 hover:bg-violet-400/10 hover:text-violet-200' => ! request()->routeIs('imports.*'),
                                ])
                                href="{{ route('imports.index') }}"
                            >
                                <span class="size-2 rounded-full bg-violet-300"></span>
                                Importar Excel
                            </a>
                        </div>

                        @if (auth()->check() && (int) auth()->id() === 1)
                            <div class="mt-6">
                                <p class="px-3 text-xs uppercase tracking-[0.25em] text-slate-500">Administración</p>
                                <div class="mt-2 grid gap-2">
                                    <a
                                        @class([
                                            'group flex items-center gap-3 rounded-full border px-3 py-2 font-medium transition-colors',
                                            'border-orange-400/25 bg-orange-400/15 text-orange-200' => request()->routeIs('admin.users.*'),
                                            'border-white/10 bg-white/5 text-slate-300 hover:border-orange-400/20 hover:bg-orange-400/10 hover:text-orange-200' => ! request()->routeIs('admin.users.*'),
                                        ])
                                        href="{{ route('admin.users.create') }}"
                                    >
                                        <span class="size-2 rounded-full bg-orange-300"></span>
                                        Usuarios
                                    </a>
                                    <a
                                        @class([
                                            'group flex items-center gap-3 rounded-full border px-3 py-2 font-medium transition-colors',
                                            'border-rose-400/25 bg-rose-400/15 text-rose-200' => request()->routeIs('admin.security.*'),
                                            'border-white/10 bg-white/5 text-slate-300 hover:border-rose-400/20 hover:bg-rose-400/10 hover:text-rose-200' => ! request()->routeIs('admin.security.*'),
                                        ])
                                        href="{{ route('admin.security.edit') }}"
                                    >
                                        <span class="size-2 rounded-full bg-rose-300"></span>
                                        Seguridad
                                    </a>
                                    <a
                                        @class([
                                            'group flex items-center gap-3 rounded-full border px-3 py-2 font-medium transition-colors',
                                            'border-cyan-400/25 bg-cyan-400/15 text-cyan-200' => request()->routeIs('settings.*'),
                                            'border-white/10 bg-white/5 text-slate-300 hover:border-cyan-400/20 hover:bg-cyan-400/10 hover:text-cyan-200' => ! request()->routeIs('settings.*'),
                                        ])
                                        href="{{ route('settings.index') }}"
                                    >
                                        <span class="size-2 rounded-full bg-cyan-300"></span>
                                        Ajustes
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>

                    <form class="border-t border-white/10 pt-4" method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="group flex w-full items-center justify-between gap-3 rounded-full border border-rose-400/25 bg-rose-400/10 px-3 py-2 font-medium text-rose-200 transition-colors hover:bg-rose-400/15">
                            <span class="flex items-center gap-3">
                                <span class="size-2 rounded-full bg-rose-300"></span>
                                Salir
                            </span>
                            <svg viewBox="0 0 14 14" class="size-3.5 stroke-rose-300 transition-transform group-hover:translate-x-0.5" fill="none" aria-hidden="true">
                                <path d="M5 3.5H3.5A1.5 1.5 0 0 0 2 5v4a1.5 1.5 0 0 0 1.5 1.5H5M8 4l3 3-3 3M11 7H5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                    </form>
                </nav>
            </aside>
            <main class="flex-1 px-4 py-5 lg:px-6 lg:py-6">
                <div class="space-y-4">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
    @livewireScripts
    @fluxScripts
</body>
</html>
