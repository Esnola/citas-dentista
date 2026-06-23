<div class="grid gap-6 xl:grid-cols-[360px_minmax(0,1fr)]">
    @if (session('status'))
        <div class="xl:col-span-2 rounded-2xl border border-emerald-400/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
            {{ session('status') }}
        </div>
    @endif

    <div class="rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur">
        <h2 class="text-xl font-semibold">Buscar cliente</h2>
        <p class="mt-2 text-sm text-slate-300">
            Escribe al menos un carácter en cualquier campo para ver coincidencias.
        </p>

        <div class="mt-6 grid gap-4">
            <flux:field>
                <flux:label>Nombre</flux:label>
                <x-formularios.input wire:model.live.debounce.300ms="filter_nombre" placeholder="Buscar por nombre" :disabled="! $canChangeAppointment" />
            </flux:field>

            <flux:field>
                <flux:label>Apellidos</flux:label>
                <x-formularios.input wire:model.live.debounce.300ms="filter_apellidos" placeholder="Buscar por apellidos" :disabled="! $canChangeAppointment" />
            </flux:field>

            <flux:field>
                <flux:label>Teléfono</flux:label>
                <x-formularios.input wire:model.live.debounce.300ms="filter_telefono" placeholder="Buscar por teléfono" :disabled="! $canChangeAppointment" />
            </flux:field>
        </div>

        @if ($hasClientSearch)
            <div class="mt-6 grid gap-2">
                @forelse ($clients as $client)
                    <button
                        type="button"
                        wire:key="appointment-form-client-{{ $client->id }}"
                        wire:click="selectClient({{ $client->id }})"
                        @disabled(! $canChangeAppointment)
                        class="rounded-2xl border border-white/10 bg-slate-950/40 p-4 text-left transition hover:border-blue-400/60 hover:bg-blue-500/10 {{ $selectedClient?->id === $client->id ? 'border-emerald-400/60 bg-emerald-500/10' : '' }}"
                    >
                        <span class="block font-medium">{{ $client->nombre }} {{ $client->apellidos }}</span>
                        <span class="mt-1 block text-sm text-slate-300">{{ $client->telefono }}</span>
                    </button>
                @empty
                    <p class="rounded-2xl border border-white/10 bg-slate-950/40 p-4 text-sm text-slate-400">
                        No hay coincidencias para esa búsqueda.
                    </p>
                @endforelse
            </div>

            @if ($clients->hasPages())
                <div class="mt-4">
                    {{ $clients->links() }}
                </div>
            @endif
        @else
            <p class="mt-6 rounded-2xl border border-white/10 bg-slate-950/40 p-4 text-sm text-slate-400">
                Las coincidencias aparecerán aquí cuando escribas al menos un carácter.
            </p>
        @endif
    </div>

    <div class="rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold">{{ $selectedAppointment ? 'Editar cita' : 'Crear cita' }}</h2>
                <p class="mt-2 text-sm text-slate-300">Gestiona fecha y hora de la cita.</p>
            </div>
            <x-botones.accion icono="back" href="{{ route('appointments.index') }}">Volver al listado</x-botones.accion>
        </div>

        @if ($selectedAppointment && ! $canChangeAppointment)
            <div class="mt-6 rounded-2xl border border-amber-400/30 bg-amber-500/10 px-4 py-3 text-sm text-amber-100">
                Esta cita ya fue enviada o pertenece al pasado. No se puede modificar; solo se puede eliminar desde el listado.
            </div>
        @endif

        <form class="mt-6 grid gap-4" wire:submit="save">
            <flux:error name="selectedClientId" />

            <div class="grid gap-4 sm:grid-cols-2">
                <flux:field>
                    <flux:label>Fecha</flux:label>
                    <x-formularios.input
                        wire:model="fecha"
                        type="date"
                        :min="$minimumSelectableDate"
                        data-no-sundays
                        :disabled="! $canChangeAppointment"
                    />
                    <flux:error name="fecha" />
                </flux:field>

                <flux:field>
                    <flux:label>Hora</flux:label>
                    <x-formularios.input
                        wire:model="hora"
                        data-time-picker
                        readonly
                        placeholder="--:--"
                        :disabled="! $canChangeAppointment"
                    />
                    <flux:error name="hora" />
                </flux:field>
            </div>

            <div class="flex flex-wrap gap-2">
                <x-botones.accion variant="add" icono="check" type="submit" :disabled="! $selectedClient || ! $canChangeAppointment">
                    {{ $selectedAppointment ? 'Guardar cambios' : 'Crear cita' }}
                </x-botones.accion>
                <x-botones.accion href="{{ route('appointments.index') }}">Cancelar</x-botones.accion>
            </div>

            <div class="rounded-2xl border border-white/10 bg-slate-950/40 p-4">
                <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Cliente seleccionado</p>
                @if ($selectedClient)
                    <p class="mt-2 font-medium">{{ $selectedClient->nombre }} {{ $selectedClient->apellidos }}</p>
                    <p class="mt-1 text-sm text-slate-300">{{ $selectedClient->telefono }}</p>
                    <p class="mt-1 text-sm text-slate-300">Alta: {{ $selectedClient->created_at?->format('d/m/Y H:i') }}</p>
                @else
                    <p class="mt-2 text-sm text-slate-300">No hay ningún cliente seleccionado todavía.</p>
                @endif
            </div>
        </form>
    </div>
</div>
