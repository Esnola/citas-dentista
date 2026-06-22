<div class="grid gap-6 xl:grid-cols-[360px_minmax(0,1fr)]">
    @if (session('status'))
        <div class="xl:col-span-2 rounded-2xl border border-emerald-400/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
            {{ session('status') }}
        </div>
    @endif

    <div class="rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur">
        <h2 class="text-xl font-semibold">Buscar destinatario</h2>
        <p class="mt-2 text-sm text-slate-300">
            Nombre, apellidos y teléfono funcionan como filtros sobre los registros guardados en la base de datos.
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
            <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Selección actual</p>
            @if ($selectedMessage)
                <p class="mt-2 font-medium">{{ $selectedMessage->full_name }}</p>
                <p class="mt-1 text-sm text-slate-300">{{ $selectedMessage->telefono }}</p>
                <p class="mt-1 text-sm text-slate-300">{{ $selectedMessage->scheduled_for?->format('d/m/Y H:i') }}</p>
                <flux:button type="button" variant="ghost" class="mt-3" wire:click="clearSelection">Limpiar selección</flux:button>
            @else
                <p class="mt-2 text-sm text-slate-300">No hay ningún registro seleccionado todavía.</p>
            @endif
        </div>

        <div class="mt-6">
            <h3 class="text-sm font-semibold uppercase tracking-[0.25em] text-slate-400">Programar</h3>
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
                    <flux:error name="selected_message_id" />
                </flux:field>

                <flux:button type="submit" :disabled="! $selectedMessage">Guardar mensaje</flux:button>
            </form>
        </div>
    </div>

    <div class="rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold">Registros encontrados</h2>
                <p class="mt-2 text-sm text-slate-300">Haz clic en un registro para usar sus datos y generar una nueva programación.</p>
            </div>
            <div class="rounded-2xl border border-white/10 bg-slate-900/60 px-4 py-3 text-sm text-slate-300">
                {{ $messages->total() }} resultados
            </div>
        </div>

        <div class="mt-4 overflow-hidden rounded-2xl border border-white/10">
            <table class="min-w-full divide-y divide-white/10 text-left text-sm">
                <thead class="bg-slate-900/70 text-slate-300">
                    <tr>
                        <th class="px-4 py-3">Nombre completo</th>
                        <th class="px-4 py-3">Cliente</th>
                        <th class="px-4 py-3">Teléfono</th>
                        <th class="px-4 py-3">Programado</th>
                        <th class="px-4 py-3">Estado</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10 bg-slate-950/40">
                    @foreach ($messages as $message)
                        <tr class="{{ $selectedMessage?->id === $message->id ? 'bg-emerald-500/10' : '' }}">
                            <td class="px-4 py-3">{{ $message->full_name }}</td>
                            <td class="px-4 py-3">
                                @if ($message->client)
                                    <a
                                        class="inline-flex rounded-full border border-emerald-400/30 bg-emerald-500/10 px-3 py-1 text-xs font-medium text-emerald-200 transition hover:border-emerald-300 hover:bg-emerald-500/20 hover:text-white"
                                        href="{{ route('clients.index', ['client' => $message->client_id]) }}"
                                        title="Abrir ficha del cliente"
                                    >
                                        {{ $message->client->nombre }} {{ $message->client->apellidos }}
                                    </a>
                                @else
                                    <span class="text-slate-500">Sin ficha</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">{{ $message->telefono }}</td>
                            <td class="px-4 py-3">{{ $message->formatted_scheduled_for }}</td>
                            <td class="px-4 py-3">{{ $message->status }}</td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex justify-end gap-2">
                                    <flux:button type="button" size="sm" variant="ghost" wire:click="selectMessage({{ $message->id }})">Usar</flux:button>
                                    <flux:button
                                        type="button"
                                        size="sm"
                                        variant="ghost"
                                        onclick="if (! confirm('¿Eliminar este mensaje?')) return; $wire.delete({{ $message->id }})"
                                    >
                                        Eliminar
                                    </flux:button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $messages->links() }}
        </div>
    </div>

    <div class="xl:col-span-2 rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur">
        <h3 class="text-lg font-semibold">Vista previa</h3>
        <p class="mt-2 text-sm text-slate-300">La previsualización toma los datos del registro seleccionado y la plantilla activa.</p>
        <div class="mt-4 rounded-2xl border border-white/10 bg-slate-950/40 p-4 text-sm leading-6 text-white">
            {{ $previewMessage }}
        </div>
    </div>
</div>
