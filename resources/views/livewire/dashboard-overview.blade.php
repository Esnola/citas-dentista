<div class="grid gap-6">
  <div class="grid gap-4 md:grid-cols-3 max-w-xl">
    <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
      <p class="text-sm text-slate-400">Pendientes</p>
      <p class="mt-2 text-3xl font-semibold">{{ $pendingCount }}</p>
    </div>
    <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
      <p class="text-sm text-slate-400">Enviados</p>
      <p class="mt-2 text-3xl font-semibold">{{ $sentCount }}</p>
    </div>
    <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
      <p class="text-sm text-slate-400">Fallidos</p>
      <p class="mt-2 text-3xl font-semibold">{{ $failedCount }}</p>
    </div>
    <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
      <p class="text-sm text-slate-400">Canceladas</p>
      <p class="mt-2 text-3xl font-semibold">{{ $cancelados }}</p>
    </div>
    <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
      <p class="text-sm text-slate-400">Caducados</p>
      <p class="mt-2 text-3xl font-semibold">{{ $caducados }}</p>
    </div>
    <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
      <p class="text-sm text-slate-400">Totales</p>
      <p class="mt-2 text-3xl font-semibold">{{ $totales }}</p>
    </div>
  </div>

  <div class="flex flex-wrap max-w-2xl items-center gap-2">
    @foreach ($targetDates as $date)
      <button
              wire:click="selectDate({{ $date['offset'] }})"
              class="rounded-full border px-4 py-2 text-sm font-medium transition-colors
                    {{ $selectedDate->toDateString() === $date['date']->toDateString()
                        ? 'border-indigo-500 bg-indigo-500/20 text-indigo-300'
                        : 'border-white/10 bg-white/5 text-slate-300 hover:bg-white/10' }}"
      >
        {{ $date['label'] }}
      </button>
    @endforeach

    <select
            wire:change="selectDate($event.target.value)"
            class="rounded-full border border-white/10 bg-white/5 px-4  py-2 text-sm font-medium text-slate-300 transition-colors hover:bg-white/10 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
    >
      <option value="">Num Días</option>
      @foreach ($futureDayOptions as $days)
        <option value="{{ $days }}" {{ $selectedDate->toDateString() === $resolvedDates[$days]->toDateString() ? 'selected' : '' }}>
          En {{ $days }} días
        </option>
      @endforeach
    </select>
  </div>


  <div class="rounded-3xl border border-white/10 bg-white/5 p-6 max-w-xl">
    <h2 class="text-xl font-semibold">
      Citas: &nbsp;{{ ucfirst($selectedDate->translatedFormat('l d \d\e F')) }}
    </h2>
    @if ($sundayWarning)
      <div class="flex items-center justify-center gap-4 rounded-2xl my-2 border border-amber-500/30 bg-amber-500/10 p-4 text-sm text-center text-amber-300 w-full">
        <x-iconos.alert/>
        {{ $sundayWarning }}
      </div>
    @endif
    <div class="mt-4 space-y-3">
      @forelse ($nextAppointments as $appointment)
        <div class="flex flex-col gap-2 rounded-2xl border border-white/10 bg-slate-900/50 p-4 md:flex-row md:items-center md:justify-between">
          <div class="flex items-center gap-3">
            <div>
              <a href="{{route('clients.edit', $appointment->client_id) }}"
                 class="font-medium text-emerald-300 hover:text-emerald-200 hover:underline"
                 aria-label="Editar datos del cliente"
                 title="Editar datos del cliente">
                {{ $appointment->client?->full_name }}
              </a>
              <span class="text-sm text-slate-300 ml-4"> {{ $appointment->scheduledFor()->translatedFormat('H:i - l, d ') }}</span>
            </div>
          </div>
          <div class="flex items-center gap-2">
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
        <p class="text-sm text-slate-400">No hay citas próximas.</p>
      @endforelse
    </div>
  </div>
</div>
