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
                    <div>
                        <p class="font-medium">{{ $appointment->client?->full_name }}</p>
                        <p class="text-sm text-slate-400">{{ $appointment->client?->telefono }}</p>
                    </div>
                    <div class="text-sm text-slate-300">
                        {{ $appointment->scheduledFor()->format('d/m/Y H:i') }}
                    </div>
                </div>
            @empty
                <p class="text-sm text-slate-400">No hay citas próximas.</p>
            @endforelse
        </div>
    </div>
</div>
