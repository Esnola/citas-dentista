@php use Carbon\Carbon; @endphp
<div class="rounded-3xl border border-white/10 bg-slate-900/30 backdrop-blur-xl">
  <div class="flex flex-col gap-3 border-b border-white/5 p-8 md:flex-row md:items-end md:justify-between">
    <div class="space-y-1">
      <h1 class="flex items-center gap-3 text-2xl font-bold tracking-tight text-white">
        <x-iconos.agenda clase="size-6 text-emerald-400"/>
        Agenda mensual
      </h1>
      <p class="text-sm text-slate-400">
        Citas de <span
                class="font-semibold text-emerald-300">{{ $selectedMonth->translatedFormat('F \\d\\e Y') }}</span>
      </p>
    </div>

    <a href="{{ route('appointments.create') }}"
       class="inline-flex min-h-11 w-fit items-center justify-center gap-2 rounded-xl border border-emerald-400/30 bg-emerald-400/10 px-4 py-2.5 text-sm font-semibold text-emerald-200 transition hover:border-emerald-300/50 hover:bg-emerald-400/15 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-400">
      <x-iconos.nueva-cita clase="size-4"/>
      Nueva cita
    </a>
  </div>

  {{--  <div class="p-8">--}}
  <div class="overflow-hidden  border border-white/10 bg-slate-950/30">
    <div class="hidden grid-cols-6 divide-x divide-white/10 border-b border-white/10 bg-slate-950/70 text-center text-[11px] font-bold uppercase tracking-wide text-slate-500 lg:grid">
      @foreach (['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'] as $weekday)
        <div class="px-3 py-2">{{ $weekday }}</div>
      @endforeach
    </div>

    <div class="bg-slate-950/20">
      @foreach ($calendarWeeks as $week)
        <div class="grid grid-cols-1 divide-y divide-white/10 sm:grid-cols-2 sm:divide-x lg:grid-cols-6 lg:divide-y-0">
          @foreach ($week as $day)
            @php
              $date = $day['date'];
              $appointments = $day['appointments'];
              $isCurrentMonth = $day['is_current_month'];
              $isToday = $day['is_today'];
            @endphp

            @if ($isCurrentMonth)
              <a href="{{ route('agenda.day', $date->toDateString()) }}"
                 wire:key="agenda-day-{{ $date->toDateString() }}"
                 class="group block min-h-44 border border-white/10 p-3 transition-colors focus-visible:outline-2 focus-visible:outline-inset focus-visible:outline-emerald-400 {{ $isToday ? 'border-emerald-300/40 bg-emerald-400/10 shadow-[inset_0_0_0_1px_rgba(110,231,183,0.12)] hover:bg-emerald-400/15' : 'bg-slate-950/10 hover:bg-white/5' }}">
                @else
                  <div wire:key="agenda-day-{{ $date->toDateString() }}"
                       class="min-h-44 border border-white/5 bg-slate-950/40 p-3 text-slate-700">
                    @endif
                    <div class="flex items-start justify-between gap-3 p-2">
                  <span>
                    <span class="block text-[11px] font-semibold uppercase tracking-wide {{ $isCurrentMonth ? 'text-slate-500' : 'text-slate-700' }}">
                      {{ $date->translatedFormat('D') }}
                    </span>
                    <span class="mt-1 flex items-baseline gap-2">
                      <span class="text-2xl font-bold {{ $isToday ? 'text-emerald-100' : ($isCurrentMonth ? 'text-slate-200' : 'text-slate-700') }}">{{ $date->format('d') }}</span>
                      <span class="text-xs font-medium {{ $isCurrentMonth ? 'text-slate-500' : 'text-slate-700' }}">{{ $date->translatedFormat('M') }}</span>
                    </span>
                  </span>

                      <span class="flex flex-col items-end gap-1">
                    @if ($isToday)
                          <span class="border rounded-md border-emerald-300/30 bg-emerald-400/10 px-2 py-0.5 text-[10px] font-bold text-emerald-100">Hoy</span>
                        @endif
                    <span class="border rounded-md px-2 py-0.5 text-[10px] font-bold {{ $appointments->isNotEmpty() ? 'border-emerald-400/25 bg-emerald-400/10 text-emerald-200' : 'border-white/10 bg-white/5 text-slate-500' }}">
                      {{ $appointments->count() }} {{ Str::plural('cita', $appointments->count()) }}
                    </span>
                  </span>
                    </div>

                    @if ($isCurrentMonth && $appointments->isNotEmpty())
                      <div class="mt-3 space-y-1.5">
                        @foreach ($appointments->take(3) as $appointment)
                          <span class="flex w-full rounded items-center gap-2 border border-white/10 bg-white/5 px-2 py-1.5 text-left text-xs text-slate-300 transition group-hover:border-emerald-400/20 group-hover:bg-emerald-400/10 group-hover:text-emerald-100">
                        <span class="font-bold text-emerald-300">{{ Carbon::parse($appointment->hora)->format('H:i') }}</span>
                        <span class="truncate">{{ $appointment->client?->nombre }}</span>
                      </span>
                        @endforeach

                        @if ($appointments->count() > 3)
                          <span class="inline-flex text-xs font-semibold text-slate-500 transition group-hover:text-emerald-300">
                        +{{ $appointments->count() - 3 }} más
                      </span>
                        @endif
                      </div>
                    @elseif ($isCurrentMonth)
                      <div class="mt-3 h-20 border border-dashed border-white/10 bg-slate-950/20"></div>
                @endif
                @if ($isCurrentMonth)
              </a>
            @else
        </div>
        @endif
      @endforeach
    </div>
    @endforeach
  </div>
  {{--  </div>--}}
</div>
</div>
