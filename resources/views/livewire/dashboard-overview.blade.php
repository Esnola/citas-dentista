@php
    $hora = (int) now()->format('H');
    if ($hora >= 6 && $hora < 12) {
        $saludo = 'Buenos días';
    } elseif ($hora >= 12 && $hora < 20) {
        $saludo = 'Buenas tardes';
    } else {
        $saludo = 'Buenas noches';
    }
@endphp

<div class="space-y-8 py-2">
  {{-- Encabezado principal estilo Hero --}}
  <div class="relative overflow-hidden rounded-3xl border border-white/10 bg-slate-900/40 p-8 md:p-10 backdrop-blur-xl">
    <div class="absolute -right-10 -top-10 h-40 w-40 rounded-full bg-emerald-500/10 blur-3xl"></div>
    <div class="absolute -left-10 -bottom-10 h-40 w-40 rounded-full bg-indigo-500/10 blur-3xl"></div>
    
    <div class="relative flex flex-col justify-between gap-6 md:flex-row md:items-center">
      <div class="space-y-2">
        <h1 class="text-3xl font-bold tracking-tight text-white md:text-4xl">
          {{ $saludo }}, <span class="bg-linear-to-r from-emerald-400 to-indigo-400 bg-clip-text text-transparent">{{ auth()->user()->name ?? 'Doctor' }}</span>
        </h1>
        <p class="text-slate-400 max-w-xl text-base">
          Bienvenido de nuevo a tu panel de control. Aquí tienes un resumen del estado de tus citas y recordatorios para hoy.
        </p>
      </div>
      <div class="flex items-center gap-3 self-start rounded-2xl border border-white/10 bg-slate-950/60 px-5 py-3 text-sm text-slate-300 md:self-center">
        <x-iconos.calendar clase="size-5 text-emerald-400" />
        <span class="font-medium">{{ ucfirst(now()->translatedFormat('l, d \\d\\e F \\d\\e Y')) }}</span>
      </div>
    </div>
  </div>

  {{-- Grid de métricas --}}
  <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6">

    <x-dashboard.metric-card title="Citas Pendientes" :value="$pendingCount" icon="reloj-arena" detail="Por enviar" badge="Espera" color="amber" />
    <x-dashboard.metric-card title="Enviados" :value="$sentCount" icon="whatsapp" detail="Entregados" badge="Éxito" color="emerald" />
    <x-dashboard.metric-card title="Fallidos" :value="$failedCount" icon="alert" detail="Atención" badge="Error" color="red" />
    <x-dashboard.metric-card title="Canceladas" :value="$cancelados" icon="papelera" detail="Inactivo" badge="Off" color="slate" />
    <x-dashboard.metric-card title="Caducados" :value="$caducados" icon="calendario-pasado" detail="Sin notificar" badge="Expira" color="orange" />
    <x-dashboard.metric-card title="Totales" :value="$totales" icon="cita" detail="Histórico" badge="Total" color="indigo" />
  </div>

</div>
