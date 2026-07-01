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
          {{ $saludo }}, <span class="bg-gradient-to-r from-emerald-400 to-indigo-400 bg-clip-text text-transparent">{{ auth()->user()->name ?? 'Doctor' }}</span>
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

  {{-- Grid de métricas con hover real, padding amplio e interactividad avanzada --}}
  <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
    {{-- Pendientes --}}
    <div class="group relative overflow-hidden rounded-3xl border border-amber-500/10 bg-slate-900/30 p-8 backdrop-blur-xl transition-all duration-300 hover:-translate-y-1.5 hover:border-amber-500/30 hover:bg-slate-900/60 hover:shadow-2xl hover:shadow-amber-500/5">
      <div class="absolute top-0 right-0 h-24 w-24 rounded-bl-full bg-amber-500/5 transition-all duration-300 group-hover:bg-amber-500/10"></div>
      <div class="flex items-center justify-between">
        <div class="space-y-1">
          <p class="text-sm font-semibold tracking-wide uppercase text-amber-400/80">Pendientes</p>
          <p class="text-4xl font-extrabold text-white tracking-tight">{{ $pendingCount }}</p>
        </div>
        <div class="rounded-2xl bg-amber-500/10 p-4 text-amber-300 transition-all duration-300 group-hover:bg-amber-500/20 group-hover:scale-110">
          <x-iconos.reloj-arena clase="size-7" />
        </div>
      </div>
      <div class="mt-6 flex items-center justify-between border-t border-white/5 pt-4">
        <span class="text-xs text-slate-400">Por enviar recordatorio</span>
        <span class="inline-flex items-center gap-1 text-xs font-medium text-amber-300 bg-amber-500/10 px-2.5 py-0.5 rounded-full">En espera</span>
      </div>
    </div>

    {{-- Enviados --}}
    <div class="group relative overflow-hidden rounded-3xl border border-emerald-500/10 bg-slate-900/30 p-8 backdrop-blur-xl transition-all duration-300 hover:-translate-y-1.5 hover:border-emerald-500/30 hover:bg-slate-900/60 hover:shadow-2xl hover:shadow-emerald-500/5">
      <div class="absolute top-0 right-0 h-24 w-24 rounded-bl-full bg-emerald-500/5 transition-all duration-300 group-hover:bg-emerald-500/10"></div>
      <div class="flex items-center justify-between">
        <div class="space-y-1">
          <p class="text-sm font-semibold tracking-wide uppercase text-emerald-400/80">Enviados</p>
          <p class="text-4xl font-extrabold text-white tracking-tight">{{ $sentCount }}</p>
        </div>
        <div class="rounded-2xl bg-emerald-500/10 p-4 text-emerald-300 transition-all duration-300 group-hover:bg-emerald-500/20 group-hover:scale-110">
          <x-iconos.whatsapp clase="size-7" />
        </div>
      </div>
      <div class="mt-6 flex items-center justify-between border-t border-white/5 pt-4">
        <span class="text-xs text-slate-400">Mensajes entregados</span>
        <span class="inline-flex items-center gap-1 text-xs font-medium text-emerald-300 bg-emerald-500/10 px-2.5 py-0.5 rounded-full">Éxito</span>
      </div>
    </div>

    {{-- Fallidos --}}
    <div class="group relative overflow-hidden rounded-3xl border border-red-500/10 bg-slate-900/30 p-8 backdrop-blur-xl transition-all duration-300 hover:-translate-y-1.5 hover:border-red-500/30 hover:bg-slate-900/60 hover:shadow-2xl hover:shadow-red-500/5">
      <div class="absolute top-0 right-0 h-24 w-24 rounded-bl-full bg-red-500/5 transition-all duration-300 group-hover:bg-red-500/10"></div>
      <div class="flex items-center justify-between">
        <div class="space-y-1">
          <p class="text-sm font-semibold tracking-wide uppercase text-red-400/80">Fallidos</p>
          <p class="text-4xl font-extrabold text-white tracking-tight">{{ $failedCount }}</p>
        </div>
        <div class="rounded-2xl bg-red-500/10 p-4 text-red-300 transition-all duration-300 group-hover:bg-red-500/20 group-hover:scale-110">
          <x-iconos.alert clase="size-7" />
        </div>
      </div>
      <div class="mt-6 flex items-center justify-between border-t border-white/5 pt-4">
        <span class="text-xs text-slate-400">Requieren atención</span>
        <span class="inline-flex items-center gap-1 text-xs font-medium text-red-300 bg-red-500/10 px-2.5 py-0.5 rounded-full">Error</span>
      </div>
    </div>

    {{-- Canceladas --}}
    <div class="group relative overflow-hidden rounded-3xl border border-slate-500/10 bg-slate-900/30 p-8 backdrop-blur-xl transition-all duration-300 hover:-translate-y-1.5 hover:border-slate-500/30 hover:bg-slate-900/60 hover:shadow-2xl hover:shadow-slate-500/5">
      <div class="absolute top-0 right-0 h-24 w-24 rounded-bl-full bg-slate-500/5 transition-all duration-300 group-hover:bg-slate-500/10"></div>
      <div class="flex items-center justify-between">
        <div class="space-y-1">
          <p class="text-sm font-semibold tracking-wide uppercase text-slate-400/80">Canceladas</p>
          <p class="text-4xl font-extrabold text-white tracking-tight">{{ $cancelados }}</p>
        </div>
        <div class="rounded-2xl bg-slate-500/10 p-4 text-slate-300 transition-all duration-300 group-hover:bg-slate-500/20 group-hover:scale-110">
          <x-iconos.papelera clase="size-7" />
        </div>
      </div>
      <div class="mt-6 flex items-center justify-between border-t border-white/5 pt-4">
        <span class="text-xs text-slate-400">Sin envío activo</span>
        <span class="inline-flex items-center gap-1 text-xs font-medium text-slate-300 bg-slate-500/10 px-2.5 py-0.5 rounded-full">Inactivo</span>
      </div>
    </div>

    {{-- Caducados --}}
    <div class="group relative overflow-hidden rounded-3xl border border-orange-500/10 bg-slate-900/30 p-8 backdrop-blur-xl transition-all duration-300 hover:-translate-y-1.5 hover:border-orange-500/30 hover:bg-slate-900/60 hover:shadow-2xl hover:shadow-orange-500/5">
      <div class="absolute top-0 right-0 h-24 w-24 rounded-bl-full bg-orange-500/5 transition-all duration-300 group-hover:bg-orange-500/10"></div>
      <div class="flex items-center justify-between">
        <div class="space-y-1">
          <p class="text-sm font-semibold tracking-wide uppercase text-orange-400/80">Caducados</p>
          <p class="text-4xl font-extrabold text-white tracking-tight">{{ $caducados }}</p>
        </div>
        <div class="rounded-2xl bg-orange-500/10 p-4 text-orange-300 transition-all duration-300 group-hover:bg-orange-500/20 group-hover:scale-110">
          <x-iconos.calendario-pasado clase="size-7" />
        </div>
      </div>
      <div class="mt-6 flex items-center justify-between border-t border-white/5 pt-4">
        <span class="text-xs text-slate-400">Citas pasadas sin notificar</span>
        <span class="inline-flex items-center gap-1 text-xs font-medium text-orange-300 bg-orange-500/10 px-2.5 py-0.5 rounded-full">Expirado</span>
      </div>
    </div>

    {{-- Totales --}}
    <div class="group relative overflow-hidden rounded-3xl border border-indigo-500/10 bg-slate-900/30 p-8 backdrop-blur-xl transition-all duration-300 hover:-translate-y-1.5 hover:border-indigo-500/30 hover:bg-slate-900/60 hover:shadow-2xl hover:shadow-indigo-500/5">
      <div class="absolute top-0 right-0 h-24 w-24 rounded-bl-full bg-indigo-500/5 transition-all duration-300 group-hover:bg-indigo-500/10"></div>
      <div class="flex items-center justify-between">
        <div class="space-y-1">
          <p class="text-sm font-semibold tracking-wide uppercase text-indigo-400/80">Totales</p>
          <p class="text-4xl font-extrabold text-white tracking-tight">{{ $totales }}</p>
        </div>
        <div class="rounded-2xl bg-indigo-500/10 p-4 text-indigo-300 transition-all duration-300 group-hover:bg-indigo-500/20 group-hover:scale-110">
          <x-iconos.cita clase="size-7" />
        </div>
      </div>
      <div class="mt-6 flex items-center justify-between border-t border-white/5 pt-4">
        <span class="text-xs text-slate-400">Total histórico de citas</span>
        <span class="inline-flex items-center gap-1 text-xs font-medium text-indigo-300 bg-indigo-500/10 px-2.5 py-0.5 rounded-full">Historial</span>
      </div>
    </div>
  </div>

  {{-- Selector de fecha y Lista de citas integrada en una sección limpia --}}
  <div class="rounded-3xl border border-white/10 bg-slate-900/30 backdrop-blur-xl">
    
    {{-- Cabecera del panel de citas con buscador/filtro de fechas interactivo --}}
    <div class="flex flex-col gap-6 border-b border-white/5 p-8 md:flex-row md:items-center md:justify-between">
      <div class="space-y-1">
        <h2 class="text-2xl font-bold tracking-tight text-white flex items-center gap-3">
          <x-iconos.proxima-cita clase="size-6 text-emerald-400" />
          Agenda del día
        </h2>
        <p class="text-sm text-slate-400">
          Mostrando citas para el <span class="font-semibold text-emerald-300">{{ $selectedDate->translatedFormat('l, d \\d\\e F') }}</span>
        </p>
      </div>
      
      {{-- Selector de fechas interactivo tipo pill premium --}}
      <div class="flex flex-wrap items-center gap-3">
        <div class="inline-flex rounded-2xl border border-white/10 bg-slate-950/60 p-1">
          @foreach ($targetDates as $date)
            <button
                    wire:click="selectDate({{ $date['offset'] }})"
                    class="rounded-xl px-4 py-2.5 text-sm font-semibold transition-all duration-200
                          {{ $selectedDate->toDateString() === $date['date']->toDateString()
                              ? 'bg-emerald-500/20 text-emerald-300 shadow-md'
                              : 'text-slate-400 hover:text-white' }}"
            >
              {{ $date['label'] }}
            </button>
          @endforeach
        </div>

        <div class="relative">
          <select
                  wire:change="selectDate($event.target.value)"
                  class="appearance-none rounded-2xl border border-white/10 bg-slate-950/60 pl-4 pr-10 py-2.5 text-sm font-semibold text-slate-300 transition-all hover:border-white/20 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 cursor-pointer"
          >
            <option value="">Otros días...</option>
            @foreach ($futureDayOptions as $days)
              <option value="{{ $days }}" {{ $selectedDate->toDateString() === $resolvedDates[$days]->toDateString() ? 'selected' : '' }}>
                En {{ $days }} días
              </option>
            @endforeach
          </select>
          <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-400">
            <x-iconos.down clase="size-4" />
          </div>
        </div>
      </div>
    </div>

    {{-- Cuerpo del listado de citas --}}
    <div class="p-8">
      @if ($sundayWarning)
        <div class="mb-6 flex items-start gap-3 rounded-2xl border border-amber-500/20 bg-amber-500/10 p-5 text-sm text-amber-300">
          <x-iconos.alert clase="size-5 shrink-0 mt-0.5" />
          <div>
            <span class="font-semibold">Nota de agenda:</span> {{ $sundayWarning }}
          </div>
        </div>
      @endif

      <div class="space-y-4">
        @forelse ($nextAppointments as $appointment)
          <div class="group flex flex-col gap-4 rounded-2xl border border-white/5 bg-slate-950/40 p-5 transition-all duration-300 hover:border-white/10 hover:bg-slate-950/80 md:flex-row md:items-center md:justify-between">
            <div class="flex items-center gap-4">
              <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-500/20 to-indigo-500/20 text-emerald-300 border border-white/5 group-hover:border-emerald-500/30 transition-all duration-300">
                <span class="text-base font-bold tracking-wider">
                  {{ strtoupper(substr($appointment->client?->nombre, 0, 1)) }}{{ strtoupper(substr($appointment->client?->apellidos ?? '', 0, 1)) }}
                </span>
              </div>
              <div class="space-y-1">
                <a href="{{ route('clients.edit', $appointment->client_id) }}"
                   class="text-base font-bold text-slate-200 transition-colors hover:text-emerald-300 hover:underline"
                   aria-label="Editar datos del cliente"
                   title="Editar datos del cliente">
                  {{ $appointment->client?->full_name }}
                </a>
                <div class="flex flex-wrap items-center gap-y-1 gap-x-3 text-sm text-slate-400">
                  <span class="flex items-center gap-1.5 text-emerald-400 bg-emerald-500/10 px-2 py-0.5 rounded-md font-medium">
                    <x-iconos.reloj-agujas clase="size-3.5" />
                    {{ $appointment->scheduledFor()->translatedFormat('H:i') }} h
                  </span>
                  <span class="text-slate-600 hidden sm:inline">•</span>
                  <span class="flex items-center gap-1.5">
                    <x-iconos.calendar clase="size-3.5 text-indigo-400" />
                    {{ $appointment->scheduledFor()->translatedFormat('l d') }}
                  </span>
                  <span class="text-slate-600 hidden sm:inline">•</span>
                  <span class="flex items-center gap-1 text-slate-300 font-medium">
                    {{ $appointment->client?->telefono }}
                  </span>
                </div>
              </div>
            </div>
            
            {{-- Acciones contextuales --}}
            <div class="flex items-center gap-3 pl-16 md:pl-0">
              <x-botones.icono-buton
                color="blue"
                icon="ojo"
                label="Ver las citas de {{ $appointment->client?->full_name }}"
                onclick="window.location.href='{{ route('appointments.index', ['client' => $appointment->client_id]) }}'"
                />
              <x-botones.icono-buton
                      color="blue"
                      icon="lapiz"
                      label="Editar esta cita de {{ $appointment->client?->full_name }}"
                      onclick="window.location.href='{{ route('appointments.index', ['client' => $appointment->client_id])}}'"
              />
            </div>
          </div>
        @empty
          <div class="flex flex-col items-center justify-center py-16 text-center border border-dashed border-white/5 rounded-3xl bg-slate-950/20">
            <div class="rounded-2xl bg-slate-900/60 p-5 border border-white/5 text-slate-500">
              <x-iconos.calendar clase="size-10" />
            </div>
            <h3 class="mt-4 text-base font-bold text-slate-300">No hay citas en esta fecha</h3>
            <p class="mt-1 text-sm text-slate-500 max-w-xs">
              No se han encontrado citas para este día. Puedes cambiar la fecha o programar una nueva cita.
            </p>
          </div>
        @endforelse
      </div>
    </div>
  </div>
</div>
