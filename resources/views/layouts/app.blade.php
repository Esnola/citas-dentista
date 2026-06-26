<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name', 'Laravel') }}</title>
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet"/>
  @fluxAppearance
  @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  @endif
  @livewireStyles
</head>
<body class="min-h-screen bg-slate-950 text-slate-100 antialiased">
@if (session('status'))
  <div
          x-data="{ show: false }"
          x-init="$nextTick(() => show = true); setTimeout(() => show = false, 3500)"
          x-show="show"
          x-transition:enter="transition transform ease-linear duration-500"
          x-transition:enter-start="translate-x-full"
          x-transition:enter-end="translate-x-0"
          x-transition:leave="transition transform ease-linear duration-500"
          x-transition:leave-start="translate-x-0"
          x-transition:leave-end="translate-x-full"
          style="display: none"
          class="fixed top-22 -right-4 z-100 flex items-center gap-4 rounded-2xl px-10 py-3 bg-green-400/10 text-sm font-medium text-green-400 inset-ring inset-ring-green-500/20 will-change-transform"
  >
    <x-iconos.ojo clase="size-8"/> {{ session('status') }}
  </div>
@endif

<div
        class="absolute inset-0 bg-[radial-gradient(circle_at_top,rgba(16,185,129,0.18),transparent_35%),linear-gradient(180deg,#020617,#0f172a)]"></div>
<div class="relative mx-auto flex min-h-screen min-w-6/8">
  <aside
          class="sticky top-0 hidden h-screen w-72 shrink-0 border-r border-white/10 bg-slate-950/70 px-4 py-5 shadow-[18px_0_60px_rgba(15,23,42,0.32)] backdrop-blur-xl xl:block">
    <nav class="flex h-full min-h-0 flex-col gap-5 text-sm">
      <a href="{{ route('dashboard') }}"
         class="group flex items-center gap-3 rounded-3xl border border-emerald-400/20 bg-emerald-400/10 p-3 text-emerald-100 transition-colors hover:bg-emerald-400/15">
                <span
                        class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl border border-emerald-300/25 bg-emerald-300/15 text-sm font-bold text-emerald-200 shadow-[0_10px_30px_rgba(16,185,129,0.14)]">WA</span>
        <span class="min-w-0">
                            <span
                                    class="block truncate text-base font-semibold">{{ config('app.name', 'Laravel') }}</span>
                            <span
                                    class="mt-0.5 block text-xs uppercase tracking-[0.22em] text-emerald-300/75">Scheduler</span>
                        </span>
      </a>

      <div class="min-h-0 flex-1 overflow-y-auto pr-1">
        <div class="grid gap-2">
          <x-navegacion.aside-link route="dashboard" route-is="dashboard" color="sky" icono="dashboard"
                                   text="Dashboard"/>
          <x-navegacion.aside-link route="clients.list" route-is="clients.*" color="emerald" icono="customer"
                                   text="Clientes"/>
          <x-navegacion.aside-link route="appointments.index" route-is="appointments.*" color="yellow"
                                   icono="calendar" icono-clase="size-5" text="Citas"/>

        </div>

        @if (auth()->check() && (int) auth()->id() === 1)
          <div class="mt-12">
            <p class=" px-3 text-xs uppercase tracking-[0.25em] font-bold text-slate-500">Administración</p>
            <div class="mt-2 grid gap-2">
              <x-navegacion.aside-link route="admin.users.create" route-is="admin.users.*" color="orange"
                                       text="Usuarios" icono="usuarios">
              </x-navegacion.aside-link>
              <x-navegacion.aside-link route="admin.security.edit" route-is="admin.security.*" color="rose"
                                       text="Seguridad" icono="seguridad"/>
              <x-navegacion.aside-link route="imports.index" route-is="imports.*" color="violet" icono="excel"
                                       text="Importar Excel"/>
              <x-navegacion.aside-link route="settings.index" route-is="settings.*" color="cyan" text="Ajustes"
                                       icono="ajustes"/>
            </div>
          </div>
        @endif
      </div>

      <form class="border-t border-white/10 pt-4" method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit"
                class="group flex w-full items-center gap-3 rounded-full border border-rose-400/25 bg-rose-400/10 px-3 py-2 font-medium text-rose-200 transition-colors hover:bg-rose-400/15">
          <svg viewBox="0 0 14 14"
               class="size-5.5 stroke-rose-300 transition-transform group-hover:translate-x-0.5"
               fill="none" aria-hidden="true">
            <path d="M5 3.5H3.5A1.5 1.5 0 0 0 2 5v4a1.5 1.5 0 0 0 1.5 1.5H5M8 4l3 3-3 3M11 7H5"
                  stroke-linecap="round"
                  stroke-linejoin="round"/>
          </svg>
          <span class="flex items-center gap-3">
                                Salir
                            </span>
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
@livewireScripts
@fluxScripts
</body>
</html>
