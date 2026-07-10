@php
    $hora = (int) $now->format('H');
    $saludo = match (true) {
        $hora >= 6 && $hora < 12 => 'Buenos días',
        $hora >= 12 && $hora < 20 => 'Buenas tardes',
        default => 'Buenas noches',
    };
    $attentionCount = $failedCount + $rescheduleCount + $upcomingWithoutReminderCount;
@endphp

<div class="space-y-6 pt-14 pb-2 xl:pt-2">
    <header class="flex flex-col gap-5 border-b border-white/10 pb-6 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="mb-2 text-xs font-semibold uppercase tracking-[0.2em] text-emerald-400">Panel diario</p>
            <h1 class="text-2xl font-bold tracking-tight text-white sm:text-3xl">
                {{ $saludo }}, {{ auth()->user()->name ?? 'Doctor' }}
            </h1>
            <p class="mt-2 flex items-center gap-2 text-sm text-slate-400">
                <x-iconos.calendar clase="size-4 text-emerald-400" />
                {{ ucfirst($now->translatedFormat('l, d \d\e F \d\e Y')) }}
            </p>
        </div>

        <nav aria-label="Acciones del dashboard" class="flex w-full gap-2 sm:w-auto">
            <a href="{{ route('agenda.index') }}" class="inline-flex min-h-11 flex-1 items-center justify-center gap-2 rounded-xl border border-white/10 bg-slate-900/70 px-4 py-2.5 text-sm font-semibold text-slate-200 transition hover:border-indigo-400/40 hover:text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-400 sm:flex-none">
                <x-iconos.agenda clase="size-4" />
                Abrir agenda
            </a>
            <a href="{{ route('appointments.create') }}" class="inline-flex min-h-11 flex-1 items-center justify-center gap-2 rounded-xl bg-emerald-500 px-4 py-2.5 text-sm font-bold text-slate-950 transition hover:bg-emerald-400 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-400 sm:flex-none">
                <x-iconos.nueva-cita clase="size-4" />
                Nueva cita
            </a>
        </nav>
    </header>

    <section aria-labelledby="today-heading" class="overflow-hidden rounded-3xl border border-white/10 bg-slate-900/60 shadow-2xl shadow-slate-950/20">
        <h2 id="today-heading" class="sr-only">Resumen de hoy</h2>
        <div class="grid lg:grid-cols-[1.2fr_1fr_0.8fr]">
            <div class="relative border-b border-white/10 p-6 sm:p-8 lg:border-b-0 lg:border-r">
                <div class="absolute right-0 top-0 h-32 w-32 bg-emerald-500/10 blur-3xl" aria-hidden="true"></div>
                <p class="text-sm font-medium text-slate-400">Citas activas de hoy</p>
                <p class="mt-3 text-6xl font-bold tracking-tighter text-white">{{ $todayCount }}</p>
                <a href="{{ route('agenda.index') }}" class="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-emerald-400 hover:text-emerald-300 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-emerald-400">
                    Ver jornada <span aria-hidden="true">→</span>
                </a>
            </div>

            <div class="border-b border-white/10 p-6 sm:p-8 lg:border-b-0 lg:border-r">
                <p class="text-sm font-medium text-slate-400">Próxima cita</p>
                @if ($nextAppointment)
                    <p class="mt-3 text-4xl font-bold tracking-tight text-white">{{ substr($nextAppointment->hora, 0, 5) }}</p>
                    <p class="mt-2 truncate text-base font-semibold text-slate-100">{{ $nextAppointment->client->nombre }} {{ $nextAppointment->client->apellidos }}</p>
                    <p class="mt-1 text-sm text-slate-400">{{ $nextAppointment->fecha->isToday() ? 'Hoy' : ucfirst($nextAppointment->fecha->translatedFormat('D, d M')) }}</p>
                @else
                    <p class="mt-5 text-lg font-semibold text-slate-200">Sin citas próximas</p>
                    <p class="mt-1 text-sm text-slate-400">La agenda futura está despejada.</p>
                @endif
            </div>

            <div class="p-6 sm:p-8">
                <p class="text-sm font-medium text-slate-400">Sin recordatorio</p>
                <p class="mt-3 text-4xl font-bold tracking-tight {{ $upcomingWithoutReminderCount > 0 ? 'text-amber-300' : 'text-emerald-300' }}">{{ $upcomingWithoutReminderCount }}</p>
                <p class="mt-2 text-sm leading-6 text-slate-400">Citas futuras activas pendientes de envío.</p>
            </div>
        </div>
    </section>

    <section aria-labelledby="attention-heading" class="rounded-2xl border p-5 {{ $attentionCount > 0 ? 'border-amber-400/25 bg-amber-400/5' : 'border-emerald-400/20 bg-emerald-400/5' }}">
        <div class="flex items-start gap-3">
            <span class="mt-0.5 grid size-9 shrink-0 place-items-center rounded-xl {{ $attentionCount > 0 ? 'bg-amber-400/10 text-amber-300' : 'bg-emerald-400/10 text-emerald-300' }}">
                @if ($attentionCount > 0)
                    <x-iconos.alert clase="size-5" />
                @else
                    <x-iconos.check clase="size-5" />
                @endif
            </span>
            <div class="min-w-0 flex-1">
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <h2 id="attention-heading" class="font-bold text-white">Requiere atención</h2>
                    <span class="text-xs font-semibold uppercase tracking-wider {{ $attentionCount > 0 ? 'text-amber-300' : 'text-emerald-300' }}">
                        {{ $attentionCount > 0 ? $attentionCount.' incidencias' : 'Todo en orden' }}
                    </span>
                </div>

                @if ($attentionCount > 0)
                    <div class="mt-4 grid gap-2 md:grid-cols-3">
                        @if ($failedCount > 0)
                            <a href="{{ route('appointments.index') }}" class="rounded-xl border border-red-400/15 bg-red-400/5 p-3 transition hover:border-red-400/30 focus-visible:outline-2 focus-visible:outline-red-400">
                                <span class="block text-sm font-semibold text-red-300">{{ $failedCount }} mensajes fallidos</span>
                                <span class="mt-1 block text-xs text-slate-400">Revisar envíos salientes</span>
                            </a>
                        @endif
                        @if ($rescheduleCount > 0)
                            <a href="{{ route('appointments.index') }}" class="rounded-xl border border-amber-400/15 bg-amber-400/5 p-3 transition hover:border-amber-400/30 focus-visible:outline-2 focus-visible:outline-amber-400">
                                <span class="block text-sm font-semibold text-amber-200">{{ $rescheduleCount }} por reprogramar</span>
                                <span class="mt-1 block text-xs text-slate-400">Concertar una nueva fecha</span>
                            </a>
                        @endif
                        @if ($upcomingWithoutReminderCount > 0)
                            <a href="{{ route('agenda.index') }}" class="rounded-xl border border-amber-400/15 bg-amber-400/5 p-3 transition hover:border-amber-400/30 focus-visible:outline-2 focus-visible:outline-amber-400">
                                <span class="block text-sm font-semibold text-amber-200">{{ $upcomingWithoutReminderCount }} sin recordatorio</span>
                                <span class="mt-1 block text-xs text-slate-400">Revisar próximas citas</span>
                            </a>
                        @endif
                    </div>
                @else
                    <p class="mt-1 text-sm text-slate-400">No hay fallos, reprogramaciones ni recordatorios pendientes.</p>
                @endif
            </div>
        </div>
    </section>

    <section aria-labelledby="next-heading" class="overflow-hidden rounded-2xl border border-white/10 bg-slate-900/45">
        <div class="flex items-center justify-between gap-4 border-b border-white/10 px-5 py-4 sm:px-6">
            <div>
                <h2 id="next-heading" class="font-bold text-white">Próximas citas</h2>
                <p class="mt-1 text-xs text-slate-400">Las siguientes cinco citas activas</p>
            </div>
            <a href="{{ route('agenda.index') }}" class="shrink-0 text-sm font-semibold text-indigo-300 hover:text-indigo-200 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-indigo-400">Ver agenda</a>
        </div>

        @if ($nextAppointments->isEmpty())
            <div class="px-6 py-10 text-center">
                <p class="font-semibold text-slate-200">No hay citas programadas</p>
                <p class="mt-1 text-sm text-slate-400">Crea una cita para empezar a organizar la agenda.</p>
            </div>
        @else
            <ul class="divide-y divide-white/5">
                @foreach ($nextAppointments as $appointment)
                    <li wire:key="dashboard-appointment-{{ $appointment->id }}" class="grid gap-4 px-5 py-4 transition hover:bg-white/[0.025] sm:grid-cols-[8rem_minmax(0,1fr)_auto] sm:items-center sm:px-6">
                        <div class="flex items-center gap-3">
                            <span class="text-xl font-bold text-white">{{ substr($appointment->hora, 0, 5) }}</span>
                            <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">{{ $appointment->fecha->isToday() ? 'Hoy' : $appointment->fecha->translatedFormat('d M') }}</span>
                        </div>
                        <div class="min-w-0">
                            <a href="{{ route('clients.appointments', $appointment->client) }}" class="block truncate font-semibold text-slate-100 hover:text-indigo-300 focus-visible:outline-2 focus-visible:outline-indigo-400">
                                {{ $appointment->client->nombre }} {{ $appointment->client->apellidos }}
                            </a>
                            <p class="mt-1 truncate text-sm text-slate-400">{{ $appointment->client->telefono }}</p>
                        </div>
                        <div class="flex items-center justify-between gap-3 sm:justify-end">
                            @if ($appointment->whatsapp_sent_at || $appointment->enviado)
                                <span class="rounded-lg bg-emerald-400/10 px-2.5 py-1 text-xs font-semibold text-emerald-300">Recordatorio enviado</span>
                            @else
                                <span class="rounded-lg bg-amber-400/10 px-2.5 py-1 text-xs font-semibold text-amber-200">Sin recordatorio</span>
                            @endif
                            <a href="{{ route('appointments.edit', $appointment) }}" aria-label="Editar cita de {{ $appointment->client->nombre }}" class="grid size-9 shrink-0 place-items-center rounded-lg border border-white/10 text-slate-400 transition hover:border-indigo-400/40 hover:text-indigo-300 focus-visible:outline-2 focus-visible:outline-indigo-400">
                                <x-iconos.lapiz clase="size-4" />
                            </a>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </section>

    <section aria-labelledby="metrics-heading">
        <div class="mb-3 flex items-end justify-between gap-4">
            <h2 id="metrics-heading" class="text-sm font-bold uppercase tracking-[0.15em] text-slate-300">Métricas secundarias</h2>
            <span class="text-xs text-slate-500">Últimos 7 días e histórico</span>
        </div>
        <dl class="grid grid-cols-2 overflow-hidden rounded-2xl border border-white/10 bg-slate-900/35 md:grid-cols-4">
            <div class="border-b border-r border-white/10 p-4 md:border-b-0 sm:p-5">
                <dt class="text-xs text-slate-400">Enviados · 7 días</dt>
                <dd class="mt-2 text-2xl font-bold text-emerald-300">{{ $sentLastSevenDays }}</dd>
            </div>
            <div class="border-b border-white/10 p-4 md:border-b-0 md:border-r sm:p-5">
                <dt class="text-xs text-slate-400">Fallidos · 7 días</dt>
                <dd class="mt-2 text-2xl font-bold {{ $failedLastSevenDays > 0 ? 'text-red-300' : 'text-slate-100' }}">{{ $failedLastSevenDays }}</dd>
            </div>
            <div class="border-r border-white/10 p-4 sm:p-5">
                <dt class="text-xs text-slate-400">Canceladas · histórico</dt>
                <dd class="mt-2 text-2xl font-bold text-slate-100">{{ $cancelledCount }}</dd>
            </div>
            <div class="p-4 sm:p-5">
                <dt class="text-xs text-slate-400">Citas · histórico</dt>
                <dd class="mt-2 text-2xl font-bold text-indigo-300">{{ $totalCount }}</dd>
            </div>
        </dl>
    </section>
</div>
