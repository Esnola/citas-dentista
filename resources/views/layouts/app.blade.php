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
            <aside class="hidden w-64 border-r border-white/10 bg-white/5 px-4 py-5 backdrop-blur xl:block">
                <nav class="mt-4 text-sm h-full flex flex-col justify-between gap-y-10 ">
                    <div class="space-y-2">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 text-lg font-semibold mb-6">
                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-2xl bg-emerald-400/20 text-emerald-300">WA</span>
                        <span>{{ config('app.name', 'Laravel') }}</span>
                    </a>
                        <a class="block rounded-xl px-3 py-2 hover:bg-white/10" href="{{ route('dashboard') }}">Dashboard</a>
                        <a class="block rounded-xl px-3 py-2 hover:bg-white/10" href="{{ route('clients.index') }}">Clientes</a>
                        <a class="block rounded-xl px-3 py-2 hover:bg-white/10"
                           href="{{ route('appointments.index') }}">Citas</a>
                        <a class="block rounded-xl px-3 py-2 hover:bg-white/10" href="{{ route('imports.index') }}">Importar
                            Excel</a>
                    @if (auth()->check() && (int) auth()->id() === 1)
                        <div class="pt-4">
                            <p class="px-3 text-xs uppercase tracking-[0.25em] text-slate-500">Administración</p>
                            <a class="mt-2 block rounded-xl px-3 py-2 hover:bg-white/10" href="{{ route('admin.users.create') }}">Usuarios</a>
                            <a class="block rounded-xl px-3 py-2 hover:bg-white/10" href="{{ route('admin.security.edit') }}">Seguridad</a>
                            <a class="block rounded-xl px-3 py-2 hover:bg-white/10" href="{{ route('settings.index') }}">Ajustes</a>
                        </div>
                    @endif
                    </div>

                    <form class="-translate-y-6" method="POST" action="{{ route('logout') }}">
                        @csrf
                        <flux:button type="submit"  icon="arrow-left-end-on-rectangle"  variant="primary" size="sm" color="orange">
                            Salir</flux:button>
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
