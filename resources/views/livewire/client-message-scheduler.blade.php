<div class="grid gap-6 xl:grid-cols-[360px_minmax(0,1fr)]">
    @if ($status)
        <div class="xl:col-span-2 rounded-2xl border border-emerald-400/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
            {{ $status }}
        </div>
    @endif

    <div class="rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur">
        <h2 class="text-xl font-semibold">Programar desde cliente</h2>
        <p class="mt-2 text-sm text-slate-300">
            Selecciona una ficha para generar una cita sin volver a escribir sus datos.
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
                <flux:button type="button" class="mt-3" wire:click="clearSelection">Limpiar selección</flux:button>
            @else
                <p class="mt-2 text-sm text-slate-300">No hay ningún cliente seleccionado todavía.</p>
            @endif
        </div>

        <div class="mt-6">
            <h3 class="text-sm font-semibold uppercase tracking-[0.25em] text-slate-400">Generar cita</h3>
            <form class="mt-4 grid gap-4" wire:submit="save">
                <flux:field>
                    <flux:label>Plantilla</flux:label>
                    <flux:select wire:model="template_key">
                        @foreach ($templateOptions as $template)
                            <option value="{{ $template['key'] }}">{{ $template['label'] }}</option>
                        @endforeach
                    </flux:select>
                    <flux:error name="template_key" />
                </flux:field>

                <div class="grid gap-4 sm:grid-cols-2">
                    <flux:field>
                        <flux:label>Fecha</flux:label>
                        <flux:input wire:model="scheduled_date" type="date" />
                        <flux:error name="scheduled_date" />
                    </flux:field>

                    <flux:field>
                        <flux:label>Hora</flux:label>
                        <flux:input wire:model="scheduled_time" type="time" />
                        <flux:error name="scheduled_time" />
                    </flux:field>
                </div>

                <flux:field>
                    <flux:error name="selectedClientId" />
                </flux:field>

                <flux:button class="action-add" type="submit" :disabled="! $selectedClient">Programar mensaje</flux:button>
            </form>
        </div>
    </div>

    <div class="rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold">Clientes</h2>
                <p class="mt-2 text-sm text-slate-300">Haz clic en un cliente para usarlo como base de la cita.</p>
                <p class="mt-1 text-xs uppercase tracking-[0.25em] text-slate-500">La alta se toma de `created_at`.</p>
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
                        <tr wire:key="schedule-client-{{ $client->id }}" class="{{ $selectedClient?->id === $client->id ? 'bg-emerald-500/10' : '' }}">
                            <td class="px-4 py-3">{{ $client->nombre }} {{ $client->apellidos }}</td>
                            <td class="px-4 py-3">{{ $client->telefono }}</td>
                            <td class="px-4 py-3">{{ $client->created_at?->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3 text-right">
                                <flux:button class="action-edit" type="button" size="sm" wire:click="selectClient({{ $client->id }})">Usar</flux:button>
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

    <div class="xl:col-span-2 rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur">
        <h3 class="text-lg font-semibold">Vista previa</h3>
        <p class="mt-2 text-sm text-slate-300">La previsualización toma los datos del cliente seleccionado y la plantilla activa.</p>
        <div class="mt-4 rounded-2xl border border-white/10 bg-slate-950/40 p-4 text-sm leading-6 text-white">
            {{ $previewMessage }}
        </div>
    </div>
</div>
