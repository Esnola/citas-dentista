<div class="grid gap-6">
    @if (session('status'))
        <div class="rounded-2xl border border-emerald-400/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
            {{ session('status') }}
        </div>
    @endif

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

    <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
        <h2 class="text-xl font-semibold">Próximos mensajes</h2>
        <div class="mt-4 space-y-3">
            @forelse ($nextMessages as $message)
                <div class="flex flex-col gap-2 rounded-2xl border border-white/10 bg-slate-900/50 p-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <p class="font-medium">{{ $message->full_name }}</p>
                        <p class="text-sm text-slate-400">{{ $message->telefono }}</p>
                    </div>
                    <div class="text-sm text-slate-300">
                        {{ $message->formatted_scheduled_for }}
                    </div>
                </div>
            @empty
                <p class="text-sm text-slate-400">No hay mensajes pendientes.</p>
            @endforelse
        </div>
    </div>
</div>
