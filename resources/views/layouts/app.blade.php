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
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(16,185,129,0.18),_transparent_35%),linear-gradient(180deg,_#020617,_#0f172a)]"></div>
        <div class="relative mx-auto flex min-h-screen max-w-7xl">
            <aside class="hidden w-72 border-r border-white/10 bg-white/5 px-6 py-8 backdrop-blur xl:block">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 text-lg font-semibold">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-emerald-400/20 text-emerald-300">WA</span>
                    <span>{{ config('app.name', 'Laravel') }}</span>
                </a>
                <nav class="mt-10 space-y-2 text-sm">
                    <a class="block rounded-xl px-4 py-3 hover:bg-white/10" href="{{ route('dashboard') }}">Dashboard</a>
                    <a class="block rounded-xl px-4 py-3 hover:bg-white/10" href="{{ route('clients.index') }}">Clientes</a>
                    <a class="block rounded-xl px-4 py-3 hover:bg-white/10" href="{{ route('appointments.index') }}">Citas</a>
                    <a class="block rounded-xl px-4 py-3 hover:bg-white/10" href="{{ route('messages.index') }}">Mensajes</a>
                    <a class="block rounded-xl px-4 py-3 hover:bg-white/10" href="{{ route('imports.index') }}">Importar Excel</a>
                    <a class="block rounded-xl px-4 py-3 hover:bg-white/10" href="{{ route('settings.index') }}">Ajustes</a>
                    @if (auth()->check() && (int) auth()->id() === 1)
                        <div class="pt-4">
                            <p class="px-4 text-xs uppercase tracking-[0.25em] text-slate-500">Administración</p>
                            <a class="mt-2 block rounded-xl px-4 py-3 hover:bg-white/10" href="{{ route('admin.users.create') }}">Usuarios</a>
                            <a class="block rounded-xl px-4 py-3 hover:bg-white/10" href="{{ route('admin.security.edit') }}">Seguridad</a>
                        </div>
                    @endif
                </nav>
            </aside>
            <main class="flex-1 px-6 py-8 lg:px-10">
                <header class="mb-8 flex items-center justify-between rounded-3xl border border-white/10 bg-white/5 px-5 py-4 backdrop-blur">
                    <div>
                        <p class="text-xs uppercase tracking-[0.3em] text-emerald-300/80">Scheduler</p>
                        <h1 class="text-xl font-semibold">{{ config('app.name', 'Laravel') }}</h1>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <flux:button type="submit" variant="ghost">Salir</flux:button>
                    </form>
                </header>
                <div class="space-y-6">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
    @livewireScripts
    @fluxScripts
</body>
</html>
