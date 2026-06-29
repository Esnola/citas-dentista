<div class="grid gap-6">
    <div class="grid gap-4 md:grid-cols-3">
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
    </div>

    <div class="flex flex-wrap gap-2">
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
    </div>

    @if ($sundayWarning)
        <div class="rounded-2xl border border-amber-500/30 bg-amber-500/10 p-4 text-sm text-amber-300">
            {{ $sundayWarning }}
        </div>
    @endif

    <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
        <h2 class="text-xl font-semibold">
            Próximas citas — {{ $selectedDate->translatedFormat('l d \d\e F') }}
        </h2>
        <div class="mt-4 space-y-3">
            @forelse ($nextAppointments as $appointment)
                <div class="flex flex-col gap-2 rounded-2xl border border-white/10 bg-slate-900/50 p-4 md:flex-row md:items-center md:justify-between">
                    <div class="flex items-center gap-3">
                        <div>
                            <a href="{{ route('appointments.index', ['client' => $appointment->client_id]) }}"
                               class="font-medium text-emerald-300 hover:text-emerald-200 hover:underline">
                                {{ $appointment->client?->full_name }}
                            </a>
                            <p class="text-sm text-slate-400">{{ $appointment->client?->telefono }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-slate-300">
                            {{ $appointment->scheduledFor()->format('d/m/Y H:i') }}
                        </span>
                        <a href="{{ route('appointments.index', ['client' => $appointment->client_id]) }}"
                           class="inline-flex items-center justify-center rounded-full border border-white/10 bg-white/5 p-2 text-slate-300 transition-colors hover:border-white/20 hover:bg-white/10 hover:text-white"
                           title="Ver citas del cliente">
                            <svg viewBox="0 0 14 14" class="size-3.5" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M7 3c-3.489 0-6.514 2.032-8 5 1.486 2.968 4.511 5 8 5s6.514-2.032 8-5c-1.486-2.968-4.511-5-8-5z"/>
                                <circle cx="7" cy="7" r="2"/>
                            </svg>
                        </a>
                        <a href="{{ route('clients.edit', $appointment->client_id) }}"
                           class="inline-flex items-center justify-center rounded-full border border-white/10 bg-white/5 p-2 text-slate-300 transition-colors hover:border-white/20 hover:bg-white/10 hover:text-white"
                           title="Editar cliente">
                            <svg viewBox="0 0 14 14" class="size-3.5" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M8.5 2.5l3 3M1 13l1-4L10.5 0.5c0.8-0.8 2-0.8 2.8 0l0.2 0.2c0.8 0.8 0.8 2 0 2.8L5 12l-4 1z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            @empty
                <p class="text-sm text-slate-400">No hay citas próximas.</p>
            @endforelse
        </div>
    </div>
</div>
