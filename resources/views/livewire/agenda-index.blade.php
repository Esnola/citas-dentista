<div class="rounded-3xl border border-white/10 bg-slate-900/30 backdrop-blur-xl">
  <div class="flex flex-col gap-6 border-b border-white/5 p-8 md:flex-row md:items-center md:justify-between">
    <div class="space-y-1">
      <h1 class="flex items-center gap-3 text-2xl font-bold tracking-tight text-white">
        <x-iconos.agenda clase="size-6 text-emerald-400"/>
        Agenda del día
      </h1>
      <p class="text-sm text-slate-400">
        Mostrando citas para el <span class="font-semibold text-emerald-300">{{ $selectedDate->translatedFormat('l, d \\d\\e F') }}</span>
      </p>
    </div>

    <div class="flex flex-wrap items-center gap-3">
      <div class="inline-flex rounded-2xl border border-white/10 bg-slate-950/60 p-1">
        @foreach ($targetDates as $date)
          <button wire:click="selectDate({{ $date['offset'] }})"
                  class="rounded-xl px-4 py-2.5 text-sm font-semibold transition-all duration-200 {{ $selectedDate->toDateString() === $date['date']->toDateString() ? 'bg-emerald-500/20 text-emerald-300 shadow-md' : 'text-slate-400 hover:text-white' }}">
            {{ $date['label'] }}
          </button>
        @endforeach
      </div>

      <div class="relative">
        <select wire:change="selectDate($event.target.value)"
                class="cursor-pointer appearance-none rounded-2xl border border-white/10 bg-slate-950/60 py-2.5 pr-10 pl-4 text-sm font-semibold text-slate-300 transition-all hover:border-white/20 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none">
          <option value="">Otros días...</option>
          @foreach ($futureDayOptions as $days)
            <option value="{{ $days }}" @selected($selectedDate->toDateString() === $resolvedDates[$days]->toDateString())>
              En {{ $days }} días
            </option>
          @endforeach
        </select>
        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-400">
          <x-iconos.down clase="size-4"/>
        </div>
      </div>
    </div>
  </div>

  <div class="p-8">
    @if ($sundayWarning)
      <div class="mb-6 flex items-start gap-3 rounded-2xl border border-amber-500/20 bg-amber-500/10 p-5 text-sm text-amber-300">
        <x-iconos.alert clase="mt-0.5 size-5 shrink-0"/>
        <div><span class="font-semibold">Nota de agenda:</span> {{ $sundayWarning }}</div>
      </div>
    @endif

    <div class="overflow-hidden rounded-2xl border border-white/10 bg-slate-950/30">
      <div class="hidden grid-cols-7 divide-x divide-white/10 border-b border-white/10 bg-slate-950/70 text-center text-[11px] font-bold uppercase tracking-wide text-slate-500 lg:grid">
        @foreach (['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'] as $weekday)
          <div class="px-3 py-2">{{ $weekday }}</div>
        @endforeach
      </div>

      <div class="grid grid-cols-1 divide-y divide-white/10 sm:grid-cols-2 sm:divide-x lg:grid-cols-7">
        @foreach ($calendarDays as $day)
          @php
            $date = $day['date'];
            $appointments = $day['appointments'];
            $isSelected = $selectedDate->toDateString() === $date->toDateString();
            $isExpanded = $expandedDateOffset === $day['offset'];
            $isToday = $date->isToday();
          @endphp

          <section wire:key="agenda-day-{{ $date->toDateString() }}"
                   class="min-h-44 p-3 transition-colors {{ $isSelected ? 'bg-emerald-500/10 ring-1 ring-inset ring-emerald-400/40' : 'bg-slate-950/10 hover:bg-white/5' }}">
            <button type="button"
                    wire:click="selectDate({{ $day['offset'] }})"
                    aria-expanded="{{ $isExpanded ? 'true' : 'false' }}"
                    class="flex w-full cursor-pointer items-start justify-between gap-3 rounded-xl p-2 text-left transition-colors hover:bg-white/5 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-400">
              <span>
                <span class="block text-[11px] font-semibold uppercase tracking-wide {{ $isSelected ? 'text-emerald-200' : 'text-slate-500' }}">
                  {{ $date->translatedFormat('D') }}
                </span>
                <span class="mt-1 flex items-baseline gap-2">
                  <span class="text-2xl font-bold {{ $isSelected ? 'text-white' : 'text-slate-200' }}">{{ $date->format('d') }}</span>
                  <span class="text-xs font-medium {{ $isSelected ? 'text-emerald-200' : 'text-slate-500' }}">{{ $date->translatedFormat('M') }}</span>
                </span>
              </span>

              <span class="flex flex-col items-end gap-1">
                @if ($isToday)
                  <span class="rounded-full border border-indigo-400/25 bg-indigo-400/10 px-2 py-0.5 text-[10px] font-bold text-indigo-200">Hoy</span>
                @endif
                <span class="rounded-full border px-2 py-0.5 text-[10px] font-bold {{ $appointments->isNotEmpty() ? 'border-emerald-400/30 bg-emerald-400/10 text-emerald-200' : 'border-white/10 bg-white/5 text-slate-500' }}">
                  {{ $appointments->count() }} {{ Str::plural('cita', $appointments->count()) }}
                </span>
              </span>
            </button>

            @if ($isExpanded)
              <div class="mt-3 space-y-2">
                @forelse ($appointments as $appointment)
                  @php($incidences = $this->appointmentIncidences($appointment))
                  <article class="rounded-xl border border-white/10 bg-slate-950/60 p-3 shadow-sm shadow-slate-950/20">
                    <div class="flex items-start justify-between gap-3">
                      <div class="min-w-0">
                        <p class="flex items-center gap-2 text-sm font-bold text-slate-100">
                          <x-iconos.reloj-agujas clase="size-4 shrink-0 text-emerald-300"/>
                          {{ \Carbon\Carbon::parse($appointment->hora)->format('H:i') }}
                        </p>
                        <a href="{{ route('clients.edit', $appointment->client_id) }}"
                           class="mt-1 block truncate text-sm font-semibold text-emerald-200 transition-colors hover:text-emerald-100 hover:underline"
                           aria-label="Editar datos del cliente">
                          {{ $appointment->client?->full_name }}
                        </a>
                      </div>
                      <div class="flex shrink-0 items-center gap-1">
                        <a href="{{ route('clients.appointments', $appointment->client_id) }}"
                           class="inline-flex size-8 items-center justify-center rounded-full border border-indigo-400/25 bg-indigo-400/10 text-indigo-200 transition hover:bg-indigo-400/15"
                           title="Ver citas de {{ $appointment->client?->full_name }}"
                           aria-label="Ver citas de {{ $appointment->client?->full_name }}">
                          <x-iconos.ojo clase="size-4"/>
                        </a>
                        <a href="{{ route('clients.edit', $appointment->client_id) }}"
                           class="inline-flex size-8 items-center justify-center rounded-full border border-blue-400/25 bg-blue-400/10 text-blue-200 transition hover:bg-blue-400/15"
                           title="Editar datos del cliente"
                           aria-label="Editar datos del cliente">
                          <x-iconos.lapiz clase="size-4"/>
                        </a>
                      </div>
                    </div>

                    @if ($incidences)
                      <div class="mt-3 flex flex-wrap gap-1.5">
                        @foreach ($incidences as $incidence)
                          <span class="inline-flex items-center gap-1 rounded-full border px-2 py-0.5 text-[10px] font-bold {{ $incidence['classes'] }}">
                            @if ($incidence['icono'] ?? false)
                              <x-iconos.doble-check clase="size-3"/>
                            @endif
                            {{ $incidence['label'] }}
                          </span>
                        @endforeach
                      </div>
                    @endif
                  </article>
                @empty
                  <div class="rounded-xl border border-dashed border-white/10 bg-slate-950/30 p-4 text-center text-sm text-slate-500">
                    Sin citas para este día.
                  </div>
                @endforelse
              </div>
            @elseif ($appointments->isNotEmpty())
              <div class="mt-3 space-y-1.5">
                @foreach ($appointments->take(3) as $appointment)
                  <button type="button"
                          wire:click="selectDate({{ $day['offset'] }})"
                          class="flex w-full items-center gap-2 rounded-lg border border-white/5 bg-white/5 px-2 py-1.5 text-left text-xs text-slate-300 transition hover:border-emerald-400/20 hover:bg-emerald-400/10 hover:text-emerald-100">
                    <span class="font-bold text-emerald-300">{{ \Carbon\Carbon::parse($appointment->hora)->format('H:i') }}</span>
                    <span class="truncate">Cita programada</span>
                  </button>
                @endforeach

                @if ($appointments->count() > 3)
                  <button type="button"
                          wire:click="selectDate({{ $day['offset'] }})"
                          class="text-xs font-semibold text-slate-500 transition hover:text-emerald-300">
                    +{{ $appointments->count() - 3 }} más
                  </button>
                @endif
              </div>
            @else
              <div class="mt-3 h-20 rounded-xl border border-dashed border-white/5 bg-slate-950/20"></div>
            @endif
          </section>
        @endforeach
      </div>
    </div>
  </div>
</div>
