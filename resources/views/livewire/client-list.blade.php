<div class="grid gap-6">
    @if (session('status'))
        <div class="rounded-2xl border border-emerald-400/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
            {{ session('status') }}
        </div>
    @endif

    <div class="rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold">Clientes registrados</h2>
                <p class="mt-2 text-sm text-slate-300">Escribe al menos un carácter en cualquier campo para buscar clientes.</p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <div class="rounded-2xl border border-white/10 bg-slate-900/60 px-4 py-3 text-sm text-slate-300">
                    {{ $clients->total() }} resultados
                </div>
                <x-botones.accion variant="add" icono="plus" href="{{ route('clients.create') }}">Nuevo cliente</x-botones.accion>
            </div>
        </div>

        <div class="mt-4 grid gap-4 sm:grid-cols-3">
            <flux:field>
                <flux:label>Nombre</flux:label>
                <x-formularios.input wire:model.live.debounce.300ms="filter_nombre" placeholder="Buscar por nombre" />
            </flux:field>

            <flux:field>
                <flux:label>Apellidos</flux:label>
                <x-formularios.input wire:model.live.debounce.300ms="filter_apellidos" placeholder="Buscar por apellidos" />
            </flux:field>

            <flux:field>
                <flux:label>Teléfono</flux:label>
                <x-formularios.input wire:model.live.debounce.300ms="filter_telefono" placeholder="Buscar por teléfono" />
            </flux:field>
        </div>

        <div class="mt-4 overflow-hidden rounded-2xl border border-white/10">
            <table class="min-w-full divide-y divide-white/10 text-left text-sm">
                <thead class="bg-slate-900/70 text-slate-300">
                    <tr>
                        <th class="px-4 py-3">
                            <button type="button" class="inline-flex cursor-pointer items-center gap-1 font-semibold text-slate-200 hover:text-white" wire:click="sortByName">
                                Nombre completo
                                <span class="text-xs text-slate-400">{{ $sort_direction === 'asc' ? '↑' : '↓' }}</span>
                            </button>
                        </th>
                        <th class="px-4 py-3">Teléfono</th>
                        <th class="px-4 py-3">Alta</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10 bg-slate-950/40">
                    @forelse ($clients as $client)
                        <tr wire:key="client-{{ $client->id }}">
                            <td class="px-4 py-3">{{ $client->nombre }} {{ $client->apellidos }}</td>
                            <td class="px-4 py-3">{{ $client->telefono }}</td>
                            <td class="px-4 py-3">{{ $client->created_at?->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex justify-end gap-2">
                                    <x-botones.accion variant="edit" size="sm" icono="edit" href="{{ route('clients.edit', $client) }}">Cita</x-botones.accion>
                                    <x-botones.accion
                                        variant="delete"
                                        size="sm"
                                        icono="delete"
                                        type="button"
                                        onclick="if (! confirm('¿Eliminar este cliente?')) return; $wire.delete({{ $client->id }})"
                                    >
                                        Eliminar
                                    </x-botones.accion>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-4 py-6 text-slate-400" colspan="4">
                                @if ($hasClientSearch)
                                    No hay clientes para esa búsqueda.
                                @else
                                    Las coincidencias aparecerán aquí cuando escribas al menos un carácter.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($hasClientSearch)
            <div class="mt-4">
                {{ $clients->links() }}
            </div>
        @endif
    </div>
</div>
