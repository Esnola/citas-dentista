@php
    $hora = (int) $now->format('H');
    $saludo = match (true) {
        $hora >= 6 && $hora < 12 => 'Buenos días',
        $hora >= 12 && $hora < 20 => 'Buenas tardes',
        default => 'Buenas noches',
    };
    $attentionCount = $failedCount + $rescheduleCount + $upcomingWithoutReminderCount;
    $nextAppointmentTime = $nextAppointment ? substr($nextAppointment->hora, 0, 5) : '—';
    $nextAppointmentClient = $nextAppointment
        ? trim($nextAppointment->client->nombre.' '.$nextAppointment->client->apellidos)
        : 'Sin citas próximas';
    $nextAppointmentDate = $nextAppointment
        ? ($nextAppointment->fecha->isToday()
            ? 'Hoy'
            : ucfirst($nextAppointment->fecha->translatedFormat('D, d M')))
        : 'La agenda futura está despejada';
@endphp

<div class="space-y-8 pt-12 pb-4 xl:pt-3">
    <section class="relative overflow-hidden rounded-[2rem] border border-white/10 bg-slate-900/70 shadow-2xl shadow-slate-950/30">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(16,185,129,0.18),transparent_34%),radial-gradient(circle_at_bottom_left,rgba(99,102,241,0.2),transparent_30%),linear-gradient(180deg,rgba(15,23,42,0.95),rgba(2,6,23,0.92))]" aria-hidden="true"></div>
        <div class="absolute -right-16 top-0 h-44 w-44 rounded-full bg-emerald-400/10 blur-3xl" aria-hidden="true"></div>
        <div class="absolute -bottom-20 left-1/3 h-44 w-44 rounded-full bg-indigo-500/10 blur-3xl" aria-hidden="true"></div>

        <div class="relative grid gap-8 p-6 sm:p-8 lg:grid-cols-[1.3fr_0.9fr] lg:p-10">
            <div class="flex flex-col justify-between gap-6">
                <div class="space-y-5">
                    <div class="inline-flex items-center gap-2 rounded-full border border-emerald-400/20 bg-emerald-400/10 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.22em] text-emerald-200">
                        <x-iconos.dashboard clase="size-3.5" />
                        Panel diario
                    </div>
                    <div class="space-y-3">
                        <h1 class="text-3xl font-bold tracking-tight text-white sm:text-4xl">
                            {{ $saludo }}, {{ auth()->user()->name ?? 'Doctor' }}
                        </h1>
                        <p class="flex flex-wrap items-center gap-2 text-sm text-slate-300">
                            <x-iconos.calendar clase="size-4 text-emerald-300" />
                            {{ ucfirst($now->translatedFormat('l, d \d\e F \d\e Y')) }}
                        </p>
                        <p class="max-w-2xl text-sm leading-6 text-slate-400">
                            Un vistazo rápido a la jornada para abrir la agenda, crear una cita o detectar incidencias pendientes sin perder tiempo.
                        </p>
                    </div>
                </div>

                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('agenda.index') }}" class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm font-semibold text-slate-100 transition hover:border-indigo-400/40 hover:bg-white/10 hover:text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-400">
                        <x-iconos.agenda clase="size-4" />
                        Abrir agenda
                    </a>
                    <a href="{{ route('appointments.create') }}" class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl bg-emerald-500 px-4 py-2.5 text-sm font-bold text-slate-950 transition hover:bg-emerald-400 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-400">
                        <x-iconos.nueva-cita clase="size-4" />
                        Nueva cita
                    </a>
                </div>

                <dl class="grid gap-3 sm:grid-cols-3">
                    <div class="rounded-2xl border border-white/10 bg-white/[0.03] p-4 backdrop-blur-sm">
                        <dt class="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400">Hoy</dt>
                        <dd class="mt-2 text-2xl font-bold tracking-tight text-white">{{ $todayCount }}</dd>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/[0.03] p-4 backdrop-blur-sm">
                        <dt class="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400">Sin recordatorio</dt>
                        <dd class="mt-2 text-2xl font-bold tracking-tight {{ $upcomingWithoutReminderCount > 0 ? 'text-amber-300' : 'text-emerald-300' }}">{{ $upcomingWithoutReminderCount }}</dd>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/[0.03] p-4 backdrop-blur-sm">
                        <dt class="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400">Incidencias</dt>
                        <dd class="mt-2 text-2xl font-bold tracking-tight {{ $attentionCount > 0 ? 'text-amber-300' : 'text-emerald-300' }}">{{ $attentionCount }}</dd>
                    </div>
                </dl>
            </div>

            <aside class="relative overflow-hidden rounded-[1.75rem] border border-white/10 bg-slate-950/75 p-5 shadow-[0_0_40px_rgba(15,23,42,0.35)]">
                <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-white/20 to-transparent" aria-hidden="true"></div>
                <div class="absolute -right-8 top-6 h-24 w-24 rounded-full bg-emerald-400/10 blur-2xl" aria-hidden="true"></div>

                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-sm font-medium text-slate-400">Próxima cita</p>
                        <p class="mt-1 text-xs uppercase tracking-[0.18em] text-slate-500">{{ $nextAppointmentDate }}</p>
                    </div>
                    <span class="rounded-full border border-white/10 bg-white/[0.03] px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-300">
                        Agenda viva
                    </span>
                </div>

                @if ($nextAppointment)
                    <div class="mt-6 space-y-5">
                        <div class="flex items-end justify-between gap-4">
                            <p class="text-5xl font-bold tracking-tight text-white sm:text-6xl">{{ $nextAppointmentTime }}</p>
                            <span class="rounded-full bg-emerald-400/10 px-3 py-1 text-xs font-semibold text-emerald-300">Siguiente turno</span>
                        </div>
                        <div>
                            <p class="truncate text-lg font-semibold text-slate-100">{{ $nextAppointmentClient }}</p>
                            <p class="mt-1 text-sm text-slate-400">{{ $nextAppointment->client->telefono }}</p>
                        </div>
                        <a href="{{ route('appointments.edit', $nextAppointment) }}" class="inline-flex items-center gap-2 text-sm font-semibold text-indigo-300 transition hover:text-indigo-200 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-indigo-400">
                            Editar cita <span aria-hidden="true">→</span>
                        </a>
                    </div>
                @else
                    <div class="mt-6 space-y-3">
                        <p class="text-3xl font-bold tracking-tight text-white">Sin citas próximas</p>
                        <p class="max-w-sm text-sm leading-6 text-slate-400">La agenda futura está despejada. Puedes abrir la vista de agenda o crear una nueva cita desde aquí.</p>
                        <a href="{{ route('appointments.create') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-emerald-300 transition hover:text-emerald-200 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-emerald-400">
                            Crear cita <span aria-hidden="true">→</span>
                        </a>
                    </div>
                @endif
            </aside>
        </div>
    </section>

    <section aria-label="Métricas principales" class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <x-dashboard.metric-card
            title="Citas hoy"
            :value="$todayCount"
            icon="calendar"
            detail="Citas activas en la jornada"
            badge="Hoy"
            color="emerald"
            route="agenda.index"
        />
        <x-dashboard.metric-card
            title="Próxima cita"
            :value="$nextAppointmentTime"
            icon="reloj-agujas"
            :detail="$nextAppointmentClient"
            :badge="$nextAppointmentDate"
            color="indigo"
            route="agenda.index"
        />
        <x-dashboard.metric-card
            title="Sin recordatorio"
            :value="$upcomingWithoutReminderCount"
            icon="whatsapp"
            detail="Citas activas pendientes de envío"
            badge="WhatsApp"
            color="amber"
            route="agenda.index"
        />
        <x-dashboard.metric-card
            title="Mensajes fallidos"
            :value="$failedCount"
            icon="alert"
            detail="Salidas con incidencia"
            :badge="$failedLastSevenDays.' esta semana'"
            color="red"
            route="appointments.index"
        />
    </section>
{{--
    <section aria-labelledby="attention-heading" class="overflow-hidden rounded-[1.75rem] border {{ $attentionCount > 0 ? 'border-amber-400/20 bg-amber-400/5' : 'border-emerald-400/20 bg-emerald-400/5' }}">
        <div class="grid gap-6 p-6 sm:p-7 lg:grid-cols-[0.95fr_1.2fr] lg:items-start">
            <div class="space-y-4">
                <div class="flex items-start gap-3">
                    <span class="mt-0.5 grid size-10 shrink-0 place-items-center rounded-2xl {{ $attentionCount > 0 ? 'bg-amber-400/10 text-amber-300' : 'bg-emerald-400/10 text-emerald-300' }}">
                        @if ($attentionCount > 0)
                            <x-iconos.alert clase="size-5" />
                        @else
                            <x-iconos.check clase="size-5" />
                        @endif
                    </span>
                    <div class="min-w-0 flex-1">
                        <h2 id="attention-heading" class="text-lg font-bold text-white">Requiere atención</h2>
                        <p class="mt-1 text-sm text-slate-400">
                            @if ($attentionCount > 0)
                                Hay elementos que conviene revisar antes de cerrar la jornada.
                            @else
                                Todo está en orden. No hay fallos, reprogramaciones ni recordatorios pendientes.
                            @endif
                        </p>
                    </div>
                </div>

                <div class="grid gap-2 text-xs font-semibold uppercase tracking-[0.18em] text-slate-400 sm:grid-cols-3">
                    <div class="rounded-2xl border border-white/10 bg-white/[0.03] px-4 py-3">
                        <span class="block text-slate-500">Fallos</span>
                        <span class="mt-1 block text-sm text-slate-100">{{ $failedCount }}</span>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/[0.03] px-4 py-3">
                        <span class="block text-slate-500">Reprogramar</span>
                        <span class="mt-1 block text-sm text-slate-100">{{ $rescheduleCount }}</span>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/[0.03] px-4 py-3">
                        <span class="block text-slate-500">Sin aviso</span>
                        <span class="mt-1 block text-sm text-slate-100">{{ $upcomingWithoutReminderCount }}</span>
                    </div>
                </div>
            </div>

            <div class="space-y-3">
                @if ($attentionCount > 0)
                    <div class="grid gap-3 md:grid-cols-3">
                        @if ($failedCount > 0)
                            <a href="{{ route('appointments.index') }}" class="rounded-2xl border border-red-400/15 bg-red-400/5 p-4 transition hover:border-red-400/30 hover:bg-red-400/10 focus-visible:outline-2 focus-visible:outline-red-400">
                                <span class="block text-sm font-semibold text-red-300">{{ $failedCount }} mensajes fallidos</span>
                                <span class="mt-1 block text-xs text-slate-400">Revisar envíos salientes</span>
                            </a>
                        @endif
                        @if ($rescheduleCount > 0)
                            <a href="{{ route('appointments.index') }}" class="rounded-2xl border border-amber-400/15 bg-amber-400/5 p-4 transition hover:border-amber-400/30 hover:bg-amber-400/10 focus-visible:outline-2 focus-visible:outline-amber-400">
                                <span class="block text-sm font-semibold text-amber-200">{{ $rescheduleCount }} por reprogramar</span>
                                <span class="mt-1 block text-xs text-slate-400">Concertar una nueva fecha</span>
                            </a>
                        @endif
                        @if ($upcomingWithoutReminderCount > 0)
                            <a href="{{ route('agenda.index') }}" class="rounded-2xl border border-amber-400/15 bg-amber-400/5 p-4 transition hover:border-amber-400/30 hover:bg-amber-400/10 focus-visible:outline-2 focus-visible:outline-amber-400">
                                <span class="block text-sm font-semibold text-amber-200">{{ $upcomingWithoutReminderCount }} sin recordatorio</span>
                                <span class="mt-1 block text-xs text-slate-400">Revisar próximas citas</span>
                            </a>
                        @endif
                    </div>
                    <p class="text-sm text-slate-400">Estos accesos te llevan directo al punto exacto donde resolver cada incidencia.</p>
                @else
                    <div class="rounded-2xl border border-emerald-400/15 bg-emerald-400/5 p-4">
                        <p class="text-sm text-emerald-200">No hay tareas urgentes. La agenda y los recordatorios están al día.</p>
                    </div>
                @endif
            </div>
        </div>
    </section>--}}

    <section aria-labelledby="next-heading" class="overflow-hidden rounded-[1.75rem] border border-white/10 bg-slate-900/45">
        <div class="flex flex-wrap items-end justify-between gap-4 border-b border-white/10 px-5 py-4 sm:px-6">
            <div>
                <h2 id="next-heading" class="font-bold text-white">Próximas citas</h2>
                <p class="mt-1 text-xs text-slate-400">Las siguientes cinco citas activas</p>
            </div>
            <a href="{{ route('agenda.index') }}" class="shrink-0 text-sm font-semibold text-indigo-300 transition hover:text-indigo-200 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-indigo-400">Ver agenda</a>
        </div>

        @if ($nextAppointments->isEmpty())
            <div class="px-6 py-10 text-center">
                <p class="font-semibold text-slate-200">No hay citas programadas</p>
                <p class="mt-1 text-sm text-slate-400">Crea una cita para empezar a organizar la agenda.</p>
            </div>
        @else
            <ul class="divide-y divide-white/5">
                @foreach ($nextAppointments as $appointment)
                    <li wire:key="dashboard-appointment-{{ $appointment->id }}" class="grid gap-4 px-5 py-4 transition hover:bg-white/2.5 sm:grid-cols-[8rem_minmax(0,1fr)_auto] sm:items-center sm:px-6">
                        <div class="flex items-center gap-3">
                            <span class="text-xl font-bold text-white">{{ substr($appointment->hora, 0, 5) }}</span>
                            <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">{{ $appointment->fecha->isToday() ? 'Hoy' : $appointment->fecha->translatedFormat('d M') }}</span>
                        </div>
                        <div class="min-w-0">
                            <a href="{{ route('clients.appointments', $appointment->client) }}" class="block truncate font-semibold text-slate-100 transition hover:text-indigo-300 focus-visible:outline-2 focus-visible:outline-indigo-400">
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
        <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-2xl border border-white/10 bg-white/[0.03] p-4">
                <p class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-400">Enviados · 7 días</p>
                <p class="mt-2 text-2xl font-bold text-emerald-300">{{ $sentLastSevenDays }}</p>
            </div>
            <div class="rounded-2xl border border-white/10 bg-white/[0.03] p-4">
                <p class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-400">Fallidos · 7 días</p>
                <p class="mt-2 text-2xl font-bold {{ $failedLastSevenDays > 0 ? 'text-red-300' : 'text-slate-100' }}">{{ $failedLastSevenDays }}</p>
            </div>
            <div class="rounded-2xl border border-white/10 bg-white/[0.03] p-4">
                <p class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-400">Canceladas · histórico</p>
                <p class="mt-2 text-2xl font-bold text-slate-100">{{ $cancelledCount }}</p>
            </div>
            <div class="rounded-2xl border border-white/10 bg-white/[0.03] p-4">
                <p class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-400">Citas · histórico</p>
                <p class="mt-2 text-2xl font-bold text-indigo-300">{{ $totalCount }}</p>
            </div>
        </div>
    </section>
</div>
