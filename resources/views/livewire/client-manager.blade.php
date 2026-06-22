<div class="grid gap-6 xl:grid-cols-[360px_minmax(0,1fr)]">
    @if (session('status'))
        <div class="xl:col-span-2 rounded-2xl border border-emerald-400/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
            {{ session('status') }}
        </div>
    @endif

    <div class="rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur">
        <h2 class="text-xl font-semibold">Buscar cliente</h2>
        <p class="mt-2 text-sm text-slate-300">
            Usa estos filtros para localizar clientes importados desde Excel o creados manualmente.
        </p>

        <div class="mt-6 grid gap-4">
            <flux:field>
                <flux:label>Nombre</flux:label>
                <flux:input wire:model.live.debounce.300ms="filter_nombre" placeholder="Buscar por nombre" />
            </flux:field>

            <flux:field>
                <flux:label>Apellidos</flux:label>
                <flux:input wire:model.live.debounce.300ms="filter_apellidos" placeholder="Buscar por apellidos" />
            </flux:field>

            <flux:field>
                <flux:label>Teléfono</flux:label>
                <flux:input wire:model.live.debounce.300ms="filter_telefono" placeholder="Buscar por teléfono" />
            </flux:field>
        </div>

        <div class="mt-6 rounded-2xl border border-white/10 bg-slate-950/40 p-4">
            <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Cliente seleccionado</p>
            @if ($selectedClient)
                <p class="mt-2 font-medium">{{ $selectedClient->nombre }} {{ $selectedClient->apellidos }}</p>
                <p class="mt-1 text-sm text-slate-300">{{ $selectedClient->telefono }}</p>
                <p class="mt-1 text-sm text-slate-300">Alta: {{ $selectedClient->created_at?->format('d/m/Y H:i') }}</p>
                <div class="mt-3 flex flex-wrap gap-2">
                    <flux:button type="button" variant="ghost" wire:click="clearSelection">Nuevo cliente</flux:button>
                    <flux:button type="button" variant="ghost" wire:click="selectClient({{ $selectedClient->id }})">Recargar</flux:button>
                </div>
            @else
                <p class="mt-2 text-sm text-slate-300">No hay ningún cliente seleccionado todavía.</p>
                <flux:button type="button" variant="ghost" class="mt-3" wire:click="clearSelection">Crear nuevo</flux:button>
            @endif
        </div>

        <div class="mt-6">
            <h3 class="text-sm font-semibold uppercase tracking-[0.25em] text-slate-400">
                {{ $selectedClient ? 'Editar cliente' : 'Crear cliente' }}
            </h3>
            <form class="mt-4 grid gap-4" wire:submit="save">
                <flux:field>
                    <flux:label>Nombre</flux:label>
                    <flux:input wire:model="nombre" />
                    <flux:error name="nombre" />
                </flux:field>

                <flux:field>
                    <flux:label>Apellidos</flux:label>
                    <flux:input wire:model="apellidos" />
                    <flux:error name="apellidos" />
                </flux:field>

                <flux:field>
                    <flux:label>Teléfono</flux:label>
                    <flux:input wire:model="telefono" placeholder="600123123" />
                    <flux:error name="telefono" />
                </flux:field>

                <flux:button type="submit">
                    {{ $selectedClient ? 'Guardar cambios' : 'Crear cliente' }}
                </flux:button>
            </form>
        </div>
    </div>

    <div class="rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold">Clientes registrados</h2>
                <p class="mt-2 text-sm text-slate-300">El listado refleja la base de datos de clientes importada desde Excel o creada manualmente.</p>
            </div>
            <div class="rounded-2xl border border-white/10 bg-slate-900/60 px-4 py-3 text-sm text-slate-300">
                {{ $clients->total() }} resultados
            </div>
        </div>

        <div class="mt-4 overflow-hidden rounded-2xl border border-white/10">
            <table class="min-w-full divide-y divide-white/10 text-left text-sm">
                <thead class="bg-slate-900/70 text-slate-300">
                    <tr>
                        <th class="px-4 py-3">Nombre completo</th>
                        <th class="px-4 py-3">Teléfono</th>
                        <th class="px-4 py-3">Alta</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10 bg-slate-950/40">
                    @forelse ($clients as $client)
                        <tr wire:key="client-{{ $client->id }}" class="{{ $selectedClient?->id === $client->id ? 'bg-emerald-500/10' : '' }}">
                            <td class="px-4 py-3">{{ $client->nombre }} {{ $client->apellidos }}</td>
                            <td class="px-4 py-3">{{ $client->telefono }}</td>
                            <td class="px-4 py-3">{{ $client->created_at?->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex justify-end gap-2">
                                    <flux:button type="button" size="sm" variant="ghost" wire:click="selectClient({{ $client->id }})">Editar</flux:button>
                                    <flux:button
                                        type="button"
                                        size="sm"
                                        variant="ghost"
                                        onclick="if (! confirm('¿Eliminar este cliente?')) return; $wire.delete({{ $client->id }})"
                                    >
                                        Eliminar
                                    </flux:button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-4 py-6 text-slate-400" colspan="4">No hay clientes para mostrar todavía.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $clients->links() }}
        </div>
    </div>
</div>
