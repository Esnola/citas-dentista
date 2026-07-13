@php use Carbon\Carbon; @endphp
<div class="grid gap-6">
  <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
    <div class="space-y-1">
      <a href="{{ route('agenda.index') }}"
         class="inline-flex items-center mb-4 text-md font-semibold text-slate-400 transition hover:text-emerald-300 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-400">
        <x-iconos.down clase="rotate-90 size-10"/>
        Agenda
      </a>
      <h1 class="flex items-center gap-3 text-2xl font-bold tracking-tight text-white">
        <x-iconos.agenda clase="size-6 text-emerald-400"/>
        {{ ucfirst($selectedDate->translatedFormat('l, d \\d\\e F')) }}
      </h1>
      <p class="text-sm text-slate-400">{{ $appointments->count() }} {{ Str::plural('cita', $appointments->count()) }}</p>
    </div>

    <a href="{{ route('appointments.create', ['date' => $selectedDate->toDateString()]) }}"
       class="inline-flex min-h-11 w-fit items-center justify-center gap-2 rounded-xl border border-emerald-400/30 bg-emerald-400/10 px-4 py-2.5 text-sm font-semibold text-emerald-200 transition hover:border-emerald-300/50 hover:bg-emerald-400/15 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-400">
      <x-iconos.nueva-cita clase="size-4"/>
      Nueva cita
    </a>
  </div>

  <div class="grid gap-3 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5">
    @forelse ($appointments as $appointment)
      @php
        $whatsappStatusBadge = $this->whatsappStatusBadge($appointment);
      @endphp
      <article wire:key="agenda-day-appointment-{{ $appointment->id }}"
               class="rounded-2xl border border-white/10 bg-slate-950/55 p-5 shadow-sm shadow-slate-950/20">
        <div class="flex items-start justify-between gap-3">
          <div class="min-w-0">
            <p class="flex items-center gap-2 text-md font-bold text-slate-100">
              <x-iconos.reloj-agujas clase="size-6 shrink-0 text-emerald-300"/>
              {{ Carbon::parse($appointment->hora)->format('H:i') }}
            </p>
            <a href="{{ route('clients.appointments', $appointment->client_id) }}"
               class="mt-2 block truncate text-base font-semibold text-emerald-200 transition-colors hover:text-emerald-100 hover:underline"
               aria-label="Editar datos del cliente">
              {{ $appointment->client?->full_name }}
            </a>
            <div class="flex gap-4 mt-2 items-center jusitfy-center text-sm text-slate-500">
              <x-iconos.telefono-mesa clase="size-6"/>
              <p class="">{{ $appointment->client->telefono }}</p>
            </div>
          </div>
          <div class="flex shrink-0 items-center gap-1">
            <a href="{{ route('clients.appointments', $appointment->client_id) }}"
               class="inline-flex size-9 items-center justify-center rounded-full border border-indigo-400/25 bg-indigo-400/10 text-indigo-200 transition hover:bg-indigo-400/15 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-400"
               title="Ver citas de {{ $appointment->client?->full_name }}"
               aria-label="Ver citas de {{ $appointment->client?->full_name }}">
              <x-iconos.ojo clase="size-4"/>
            </a>
            <a href="{{ route('clients.edit', $appointment->client_id) }}"
               class="inline-flex size-9 items-center justify-center rounded-full border border-blue-400/25 bg-blue-400/10 text-blue-200 transition hover:bg-blue-400/15 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-400"
               title="Editar datos del cliente"
               aria-label="Editar datos del cliente">
              <x-iconos.lapiz clase="size-4"/>
            </a>
          </div>
        </div>

        @if ($whatsappStatusBadge)
          <div class="mt-4 flex justify-end flex-wrap gap-1.5">
            <span class="inline-flex items-center gap-1 rounded-full border px-2 py-0.5 text-[10px] font-bold shadow {{ $whatsappStatusBadge['classes'] }}">
            <x-dynamic-component :component="'iconos.' . $whatsappStatusBadge['icono']" class/>
              {{ $whatsappStatusBadge['label'] }}
            </span>
          </div>
        @endif
      </article>
    @empty
      <div class="rounded-2xl border border-dashed border-white/10 bg-slate-950/35 p-8 text-center text-sm text-slate-500 md:col-span-2 xl:col-span-3">
        Sin citas para este día.
      </div>
    @endforelse
  </div>
</div>
