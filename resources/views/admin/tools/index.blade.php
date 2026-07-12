@extends('layouts.app')

@section('content')
  <div class="grid gap-6 max-w-5xl mx-auto">

    {{-- Exportar / Importar por tabla --}}
    <div class="rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur">
      <h2 class="text-xl font-semibold">Exportar / Importar por tabla</h2>
      <p class="mt-2 text-sm text-slate-400">Descarga o restaura clientes, citas y usuarios individualmente.</p>
      <div class="mt-6">
        <livewire:table-backup/>
      </div>
    </div>

    {{-- Ajustes --}}
    <div class="rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur">
      <h2 class="text-xl font-semibold">Ajustes del Sistema</h2>
      <p class="mt-2 text-sm text-slate-400">Exporta o importa la configuración de WhatsApp, plantillas y más.</p>
      <div class="mt-6">
        <livewire:settings-backup/>
      </div>
    </div>

    {{-- Toda la Base de datos --}}
    <div class="rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur">
      <h2 class="text-xl font-semibold">Toda la Base de datos</h2>
      <p class="mt-2 text-sm text-slate-400">Exporta o importa la base de datos completa (clientes, citas, mensajes, ajustes y más).</p>
      <div class="mt-6">
        <livewire:database-backup/>
      </div>
    </div>

  </div>
@endsection
