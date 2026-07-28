<div class="grid gap-5">
  <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
    <div>
      <h1 class="flex items-center gap-3 text-2xl font-bold tracking-tight text-white">
        <x-iconos.calendar clase="size-6 text-sky-300"/>
        Calendario
      </h1>
      <p class="mt-1 text-sm text-slate-400">Citas programadas por día del mes.</p>
    </div>

    <div class="inline-flex w-fit items-center overflow-hidden rounded-xl border border-white/10 bg-slate-950/70">
      <button type="button"
              wire:click="previousMonth"
              class="flex size-11 items-center justify-center text-slate-300 transition hover:bg-white/10 hover:text-white"
              aria-label="Mes anterior">
        <x-iconos.down clase="size-4 rotate-90"/>
      </button>
      <button type="button"
              wire:click="currentMonth"
              class="min-h-11 border-x border-white/10 px-5 text-sm font-semibold text-slate-200 transition hover:bg-white/10 hover:text-white">
        Hoy
      </button>
      <button type="button"
              wire:click="nextMonth"
              class="flex size-11 items-center justify-center text-slate-300 transition hover:bg-white/10 hover:text-white"
              aria-label="Mes siguiente">
        <x-iconos.down clase="size-4 -rotate-90"/>
      </button>
    </div>
  </div>

  <section
          class="overflow-hidden rounded-2xl border border-white/10 bg-slate-950/40 text-slate-100 shadow-2xl shadow-slate-950/30">
    <div class="border-b border-white/10 bg-slate-900/80 px-5 py-5 text-center">
      <h2 class="text-3xl font-black uppercase tracking-wide text-white">
        {{ ucfirst($selectedMonth->translatedFormat('F')) }}
      </h2>
      <p class="mt-1 text-sm font-bold tracking-[0.35em] text-sky-300/80">{{ $selectedMonth->format('Y') }}</p>
    </div>

    <div class="grid grid-cols-7 border-b border-white/10 bg-slate-950/80 text-center text-xs font-black uppercase tracking-wide text-slate-300">
      @foreach (['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'] as $weekday)
        <div class="border-r border-white/10 px-2 py-3 last:border-r-0">
          <span class="hidden md:inline">{{ $weekday }}</span>
          <span class="md:hidden">{{ Str::substr($weekday, 0, 3) }}</span>
        </div>
      @endforeach
    </div>

    <div class="bg-slate-950/30">
      @foreach ($calendarWeeks as $week)
        <div class="grid grid-cols-7 border-b border-white/10 last:border-b-0">
          @foreach ($week as $day)
            @php
              $date = $day['date'];
              $appointmentsCount = $day['appointments_count'];
              $inactiveAppointmentsCount = $day['inactive_appointments_count'];
              $isCurrentMonth = $day['is_current_month'];
              $isSunday = $day['is_sunday'];
              $isToday = $date->isToday();
              $isPast = $isCurrentMonth && ! $isSunday && $date->lt(now(config('app.timezone'))->startOfDay());
            @endphp

            @if ($isCurrentMonth && ! $isSunday)
              <a href="{{ route('agenda.day', $date->toDateString()) }}"
                 class="relative flex min-h-28 flex-col border-r border-white/10 p-3 transition-colors last:border-r-0 focus-visible:outline-2 focus-visible:outline-inset focus-visible:outline-sky-300 sm:min-h-32 md:min-h-36 lg:min-h-40 {{ $isPast ? 'bg-zinc-950/35 text-slate-600 hover:bg-zinc-950/45' : 'bg-slate-900/45 hover:bg-slate-900/70' }}"
                 aria-label="Ver agenda del {{ $date->translatedFormat('j \\d\\e F') }}">
            @else
              <div class="relative flex min-h-28 flex-col border-r border-white/10 p-3 transition-colors last:border-r-0 sm:min-h-32 md:min-h-36 lg:min-h-40 bg-slate-950/55 text-slate-600">
            @endif
              <div class="flex items-start justify-between gap-2">
                <span class="grid size-14 place-items-center text-xl font-bold leading-none {{ $isToday && ! $isSunday ? 'rounded-full bg-sky-600/50 text-white shadow-sm shadow-sky-500/20' : ($isPast ? 'text-slate-700/70' : ($isCurrentMonth && ! $isSunday ? 'text-slate-100/70' : 'text-slate-600/50')) }}">
                    {{ $date->format('j') }}
                </span>
              </div>

              @if (! $isSunday)
                <div class="grid flex-1 place-items-center">
                  <div class="text-center">
                    <span class="block text-4xl font-black leading-none {{ $appointmentsCount > 0 && $isCurrentMonth && ! $isPast ? 'text-sky-300/60' : ($isPast ? 'text-slate-700/60' : 'text-slate-700') }}">
                      {{ $appointmentsCount }}
                    </span>
                    <span class="mt-1 block text-[10px] font-black uppercase tracking-wide {{ $appointmentsCount > 0 && $isCurrentMonth && ! $isPast ? 'text-sky-300' : ($isPast ? 'text-slate-700/60' : 'text-slate-600') }}">
                      Total {{ Str::plural('cita', $appointmentsCount) }}
                    </span>
                    <div class="mt-3 rounded-lg border border-white/10 bg-slate-950/35 px-3 py-1.5">
                      <span class="block text-xl font-black leading-none {{ $inactiveAppointmentsCount > 0 && $isCurrentMonth && ! $isPast ? 'text-amber-600/50' : ($isPast ? 'text-slate-700/50' : 'text-slate-700/50') }}">
                        {{ $inactiveAppointmentsCount }}
                      </span>
                      <span class="mt-0.5 block text-[9px] font-black uppercase tracking-wide {{ $inactiveAppointmentsCount > 0 && $isCurrentMonth && ! $isPast ? 'text-amber-600/50' : ($isPast ? 'text-slate-700/50' : 'text-slate-600/50') }}">
                        No activas
                      </span>
                    </div>
                  </div>
                </div>
              @endif
            @if ($isCurrentMonth && ! $isSunday)
              </a>
            @else
              </div>
            @endif
          @endforeach
        </div>
      @endforeach
    </div>
  </section>
</div>
