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

  {{-- Grid de métricas --}}
  <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6">
    {{-- Pendientes --}}
    <div class="group relative overflow-hidden rounded-2xl border border-amber-500/10 bg-slate-900/30 p-4 backdrop-blur-xl transition-all duration-300 hover:-translate-y-1 hover:border-amber-500/30 hover:bg-slate-900/60 hover:shadow-xl hover:shadow-amber-500/5">
      <div class="absolute top-0 right-0 h-16 w-16 rounded-bl-full bg-amber-500/5 transition-all duration-300 group-hover:bg-amber-500/10"></div>
      <div class="flex items-center justify-between">
        <div class="space-y-0.5">
          <p class="text-xs font-semibold tracking-wide uppercase text-amber-400/80">Pendientes</p>
          <p class="text-2xl font-extrabold text-white tracking-tight">{{ $pendingCount }}</p>
        </div>
        <div class="rounded-xl bg-amber-500/10 p-2.5 text-amber-300 transition-all duration-300 group-hover:bg-amber-500/20 group-hover:scale-110">
          <x-iconos.reloj-arena clase="size-5" />
        </div>
      </div>
      <div class="mt-3 flex items-center justify-between border-t border-white/5 pt-2.5">
        <span class="text-[10px] text-slate-400">Por enviar</span>
        <span class="inline-flex items-center text-[10px] font-medium text-amber-300 bg-amber-500/10 px-1.5 py-0.5 rounded-full">Espera</span>
      </div>
    </div>

    {{-- Enviados --}}
    <div class="group relative overflow-hidden rounded-2xl border border-emerald-500/10 bg-slate-900/30 p-4 backdrop-blur-xl transition-all duration-300 hover:-translate-y-1 hover:border-emerald-500/30 hover:bg-slate-900/60 hover:shadow-xl hover:shadow-emerald-500/5">
      <div class="absolute top-0 right-0 h-16 w-16 rounded-bl-full bg-emerald-500/5 transition-all duration-300 group-hover:bg-emerald-500/10"></div>
      <div class="flex items-center justify-between">
        <div class="space-y-0.5">
          <p class="text-xs font-semibold tracking-wide uppercase text-emerald-400/80">Enviados</p>
          <p class="text-2xl font-extrabold text-white tracking-tight">{{ $sentCount }}</p>
        </div>
        <div class="rounded-xl bg-emerald-500/10 p-2.5 text-emerald-300 transition-all duration-300 group-hover:bg-emerald-500/20 group-hover:scale-110">
          <x-iconos.whatsapp clase="size-5" />
        </div>
      </div>
      <div class="mt-3 flex items-center justify-between border-t border-white/5 pt-2.5">
        <span class="text-[10px] text-slate-400">Entregados</span>
        <span class="inline-flex items-center text-[10px] font-medium text-emerald-300 bg-emerald-500/10 px-1.5 py-0.5 rounded-full">Éxito</span>
      </div>
    </div>

    {{-- Fallidos --}}
    <div class="group relative overflow-hidden rounded-2xl border border-red-500/10 bg-slate-900/30 p-4 backdrop-blur-xl transition-all duration-300 hover:-translate-y-1 hover:border-red-500/30 hover:bg-slate-900/60 hover:shadow-xl hover:shadow-red-500/5">
      <div class="absolute top-0 right-0 h-16 w-16 rounded-bl-full bg-red-500/5 transition-all duration-300 group-hover:bg-red-500/10"></div>
      <div class="flex items-center justify-between">
        <div class="space-y-0.5">
          <p class="text-xs font-semibold tracking-wide uppercase text-red-400/80">Fallidos</p>
          <p class="text-2xl font-extrabold text-white tracking-tight">{{ $failedCount }}</p>
        </div>
        <div class="rounded-xl bg-red-500/10 p-2.5 text-red-300 transition-all duration-300 group-hover:bg-red-500/20 group-hover:scale-110">
          <x-iconos.alert clase="size-5" />
        </div>
      </div>
      <div class="mt-3 flex items-center justify-between border-t border-white/5 pt-2.5">
        <span class="text-[10px] text-slate-400">Atención</span>
        <span class="inline-flex items-center text-[10px] font-medium text-red-300 bg-red-500/10 px-1.5 py-0.5 rounded-full">Error</span>
      </div>
    </div>

    {{-- Canceladas --}}
    <div class="group relative overflow-hidden rounded-2xl border border-slate-500/10 bg-slate-900/30 p-4 backdrop-blur-xl transition-all duration-300 hover:-translate-y-1 hover:border-slate-500/30 hover:bg-slate-900/60 hover:shadow-xl hover:shadow-slate-500/5">
      <div class="absolute top-0 right-0 h-16 w-16 rounded-bl-full bg-slate-500/5 transition-all duration-300 group-hover:bg-slate-500/10"></div>
      <div class="flex items-center justify-between">
        <div class="space-y-0.5">
          <p class="text-xs font-semibold tracking-wide uppercase text-slate-400/80">Canceladas</p>
          <p class="text-2xl font-extrabold text-white tracking-tight">{{ $cancelados }}</p>
        </div>
        <div class="rounded-xl bg-slate-500/10 p-2.5 text-slate-300 transition-all duration-300 group-hover:bg-slate-500/20 group-hover:scale-110">
          <x-iconos.papelera clase="size-5" />
        </div>
      </div>
      <div class="mt-3 flex items-center justify-between border-t border-white/5 pt-2.5">
        <span class="text-[10px] text-slate-400">Inactivo</span>
        <span class="inline-flex items-center text-[10px] font-medium text-slate-300 bg-slate-500/10 px-1.5 py-0.5 rounded-full">Off</span>
      </div>
    </div>

    {{-- Caducados --}}
    <div class="group relative overflow-hidden rounded-2xl border border-orange-500/10 bg-slate-900/30 p-4 backdrop-blur-xl transition-all duration-300 hover:-translate-y-1 hover:border-orange-500/30 hover:bg-slate-900/60 hover:shadow-xl hover:shadow-orange-500/5">
      <div class="absolute top-0 right-0 h-16 w-16 rounded-bl-full bg-orange-500/5 transition-all duration-300 group-hover:bg-orange-500/10"></div>
      <div class="flex items-center justify-between">
        <div class="space-y-0.5">
          <p class="text-xs font-semibold tracking-wide uppercase text-orange-400/80">Caducados</p>
          <p class="text-2xl font-extrabold text-white tracking-tight">{{ $caducados }}</p>
        </div>
        <div class="rounded-xl bg-orange-500/10 p-2.5 text-orange-300 transition-all duration-300 group-hover:bg-orange-500/20 group-hover:scale-110">
          <x-iconos.calendario-pasado clase="size-5" />
        </div>
      </div>
      <div class="mt-3 flex items-center justify-between border-t border-white/5 pt-2.5">
        <span class="text-[10px] text-slate-400">Sin notificar</span>
        <span class="inline-flex items-center text-[10px] font-medium text-orange-300 bg-orange-500/10 px-1.5 py-0.5 rounded-full">Expira</span>
      </div>
    </div>

    {{-- Totales --}}
    <div class="group relative overflow-hidden rounded-2xl border border-indigo-500/10 bg-slate-900/30 p-4 backdrop-blur-xl transition-all duration-300 hover:-translate-y-1 hover:border-indigo-500/30 hover:bg-slate-900/60 hover:shadow-xl hover:shadow-indigo-500/5">
      <div class="absolute top-0 right-0 h-16 w-16 rounded-bl-full bg-indigo-500/5 transition-all duration-300 group-hover:bg-indigo-500/10"></div>
      <div class="flex items-center justify-between">
        <div class="space-y-0.5">
          <p class="text-xs font-semibold tracking-wide uppercase text-indigo-400/80">Totales</p>
          <p class="text-2xl font-extrabold text-white tracking-tight">{{ $totales }}</p>
        </div>
        <div class="rounded-xl bg-indigo-500/10 p-2.5 text-indigo-300 transition-all duration-300 group-hover:bg-indigo-500/20 group-hover:scale-110">
          <x-iconos.cita clase="size-5" />
        </div>
      </div>
      <div class="mt-3 flex items-center justify-between border-t border-white/5 pt-2.5">
        <span class="text-[10px] text-slate-400">Histórico</span>
        <span class="inline-flex items-center text-[10px] font-medium text-indigo-300 bg-indigo-500/10 px-1.5 py-0.5 rounded-full">Total</span>
      </div>
    </div>
  </div>

  {{-- Selector de fecha y Lista de citas integrada en una sección limpia --}}
  <div class="rounded-3xl border border-white/10 bg-slate-900/30 backdrop-blur-xl">
    
    {{-- Cabecera del panel de citas con buscador/filtro de fechas interactivo --}}
    <div class="flex flex-col gap-6 border-b border-white/5 p-8 md:flex-row md:items-center md:justify-between">
      <div class="space-y-1">
        <h2 class="text-2xl font-bold tracking-tight text-white flex items-center gap-3">
          <x-iconos.agenda clase="size-6 text-emerald-400" />
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
                              : 'text-slate-400 hover:text-white' }}" >
              {{ $date['label'] }}
            </button>
          @endforeach
        </div>

        <div class="relative">
          <select
                  wire:change="selectDate($event.target.value)"
                  class="appearance-none rounded-2xl border border-white/10 bg-slate-950/60 pl-4 pr-10 py-2.5 text-sm font-semibold text-slate-300 transition-all hover:border-white/20 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 cursor-pointer">
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

      <div class="space-y-6">
        @forelse ($nextAppointments as $hora => $citasHora)
          <div class="space-y-3">
            {{-- Encabezado de hora --}}
            <div class="flex items-center gap-3">
              <div class="flex items-center gap-2 rounded-xl bg-emerald-500/10 border border-emerald-500/20 px-3 py-1.5">
                <x-iconos.reloj-agujas clase="size-4 text-emerald-400" />
                <span class="text-sm font-bold text-emerald-300">{{ \Carbon\Carbon::parse($hora)->format('H:i') }} </span>
              </div>
              <div class="h-px flex-1 bg-white/5"></div>
              <span class="text-xs text-slate-500">{{ $citasHora->count() }} {{ Str::plural('cita', $citasHora->count()) }}</span>
            </div>
            {{-- Citas de esta hora --}}
            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
              @foreach ($citasHora as $appointment)
                @php($incidences = $this->appointmentIncidences($appointment))
                <div class="group flex flex-col gap-3 rounded-2xl border border-white/5 bg-slate-950/40 p-4 transition-all duration-300 hover:border-white/10 hover:bg-slate-950/80">
                  <div class="space-y-2">
                    <div class="flex items-center justify-between gap-3">
                        <a href="{{ route('clients.edit', $appointment->client_id) }}"
                           class="block truncate text-sm font-bold text-slate-200 transition-colors hover:text-emerald-300 hover:underline"
                           aria-label="Editar datos del cliente"
                           title="Editar datos del cliente">
                          {{ $appointment->client?->full_name }}
                        </a>
                      <div class="gap-2">
                        <x-botones.icono-buton
                                color="indigo"
                                icon="ojo"
                                especial2="fill-blue-500/50 size-6"
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
                    @if ($incidences)
                      <div class="flex flex-wrap gap-2">
                        @foreach ($incidences as $incidence)
                          <span class="inline-flex items-center rounded-full border px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide {{ $incidence['classes'] }}">

                            @if (isset($incidence['icono']))
                              <x-iconos.check clase="size-4 mr-2 text-green-300 rounded-3xl" />
                            @endif
                            {{ $incidence['label'] }}
                          </span>
                        @endforeach
                      </div>
                    @endif
                  </div>
                </div>
              @endforeach
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
