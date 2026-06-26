<div class="grid gap-6">
    <div class="rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold">{{ $selectedClient ? 'Editar cliente' : 'Crear cliente' }}</h2>
                <p class="mt-2 text-sm text-slate-300">Gestiona los datos básicos de la ficha del cliente.</p>
            </div>
            <x-botones.accion variant="indigo" size="sm" icono="back" href="{{ route('clients.list') }}">Volver al listado</x-botones.accion>
        </div>

        <form class="mt-6 grid grid-cols-3 gap-4" wire:submit="save">
            <flux:field>
                <flux:label>Nombre</flux:label>
                <x-formularios.input wire:model="nombre" />
                <flux:error name="nombre" />
            </flux:field>

            <flux:field>
                <flux:label>Apellidos</flux:label>
                <x-formularios.input wire:model="apellidos" />
                <flux:error name="apellidos" />
            </flux:field>

            <flux:field>
                <flux:label>Teléfono</flux:label>
                <x-formularios.input wire:model="telefono" placeholder="600123123" />
                <flux:error name="telefono" />
            </flux:field>

            <div class="flex flex-wrap gap-2 mt-4">
                <x-botones.accion variant="add" size="sm" icono="check" type="submit">
                    {{ $selectedClient ? 'Guardar cambios' : 'Crear cliente' }}
                </x-botones.accion>
                <x-botones.accion variant="indigo" size="sm" href="{{ route('clients.list') }}">Volver</x-botones.accion>
            </div>
        </form>
    </div>

</div>
