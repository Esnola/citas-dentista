<div class="grid gap-6 xl:grid-cols-[360px_minmax(0,1fr)]">
    @if (session('status'))
        <div class="xl:col-span-2 rounded-2xl border border-emerald-400/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
            {{ session('status') }}
        </div>
    @endif

    <div class="rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur">
        <h2 class="text-xl font-semibold">Buscar cliente</h2>
        <p class="mt-2 text-sm text-slate-300">
            Selecciona el paciente desde el que quieres crear o editar una cita.
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
                    <flux:button type="button" variant="ghost" wire:click="clearSelection">Limpiar</flux:button>
                    <flux:button type="button" variant="ghost" wire:click="selectClient({{ $selectedClient->id }})">Recargar</flux:button>
                </div>
            @else
                <p class="mt-2 text-sm text-slate-300">No hay ningún cliente seleccionado todavía.</p>
            @endif
        </div>

        <div class="mt-6">
            <h3 class="text-sm font-semibold uppercase tracking-[0.25em] text-slate-400">
                {{ $selectedAppointment ? 'Editar cita' : 'Crear cita' }}
            </h3>
            <form class="mt-4 grid gap-4" wire:submit="save">
                <flux:field>
                    <flux:label>Cliente</flux:label>
                    <flux:select wire:model="selectedClientId">
                        <option value="">Selecciona un cliente</option>
                        @foreach ($clients as $client)
                            <option value="{{ $client->id }}">{{ $client->nombre }} {{ $client->apellidos }} - {{ $client->telefono }}</option>
                        @endforeach
                    </flux:select>
                    <flux:error name="selectedClientId" />
                </flux:field>

                <div class="grid gap-4 sm:grid-cols-2">
                    <flux:field>
                        <flux:label>Fecha</flux:label>
                        <flux:input wire:model="fecha" type="date" />
                        <flux:error name="fecha" />
                    </flux:field>

                    <flux:field>
                        <flux:label>Hora</flux:label>
                        <flux:input wire:model="hora" type="time" />
                        <flux:error name="hora" />
                    </flux:field>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <flux:field>
                        <flux:label>Enviado</flux:label>
                        <flux:select wire:model="enviado">
                            <option value="0">No</option>
                            <option value="1">Sí</option>
                        </flux:select>
                        <flux:error name="enviado" />
                    </flux:field>

                    <flux:field>
                        <flux:label>Activo</flux:label>
                        <flux:select wire:model="activo">
                            <option value="1">Sí</option>
                            <option value="0">No</option>
                        </flux:select>
                        <flux:error name="activo" />
                    </flux:field>
                </div>

                <div class="flex flex-wrap gap-2">
                    <flux:button type="submit" :disabled="! $selectedClient">Guardar cita</flux:button>
                    <flux:button type="button" variant="ghost" wire:click="clearSelection">Nueva cita</flux:button>
                </div>
            </form>
        </div>
    </div>

    <div class="rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold">Citas registradas</h2>
                <p class="mt-2 text-sm text-slate-300">Cada cita queda enlazada con su cliente y conserva su estado de envío y activación.</p>
            </div>
            <div class="rounded-2xl border border-white/10 bg-slate-900/60 px-4 py-3 text-sm text-slate-300">
                {{ $appointments->total() }} resultados
            </div>
        </div>

        <div class="mt-4 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            <flux:field>
                <flux:label>Enviado</flux:label>
                <flux:select wire:model.live="filter_enviado">
                    <option value="">Todas</option>
                    <option value="1">Sí</option>
                    <option value="0">No</option>
                </flux:select>
            </flux:field>

            <flux:field>
                <flux:label>Activo</flux:label>
                <flux:select wire:model.live="filter_activo">
                    <option value="">Todas</option>
                    <option value="1">Sí</option>
                    <option value="0">No</option>
                </flux:select>
            </flux:field>
        </div>

        <div class="mt-4 overflow-hidden rounded-2xl border border-white/10">
            <table class="min-w-full divide-y divide-white/10 text-left text-sm">
                <thead class="bg-slate-900/70 text-slate-300">
                    <tr>
                        <th class="px-4 py-3">Cliente</th>
                        <th class="px-4 py-3">Fecha</th>
                        <th class="px-4 py-3">Hora</th>
                        <th class="px-4 py-3">Enviado</th>
                        <th class="px-4 py-3">Activo</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10 bg-slate-950/40">
                    @forelse ($appointments as $appointment)
                        <tr wire:key="appointment-{{ $appointment->id }}" class="{{ $selectedAppointment?->id === $appointment->id ? 'bg-emerald-500/10' : '' }}">
                            <td class="px-4 py-3">
                                <a
                                    href="{{ route('clients.index', ['client' => $appointment->client_id]) }}"
                                    class="font-medium text-emerald-300 hover:text-emerald-200"
                                >
                                    {{ $appointment->client?->nombre }} {{ $appointment->client?->apellidos }}
                                </a>
                                <p class="text-xs text-slate-400">{{ $appointment->client?->telefono }}</p>
                            </td>
                            <td class="px-4 py-3">{{ $appointment->fecha?->format('d/m/Y') }}</td>
                            <td class="px-4 py-3">{{ $appointment->hora }}</td>
                            <td class="px-4 py-3">
                                <span class="rounded-full px-2.5 py-1 text-xs font-medium {{ $appointment->enviado ? 'bg-emerald-500/20 text-emerald-200' : 'bg-amber-500/20 text-amber-200' }}">
                                    {{ $appointment->enviado ? 'Sí' : 'No' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="rounded-full px-2.5 py-1 text-xs font-medium {{ $appointment->activo ? 'bg-sky-500/20 text-sky-200' : 'bg-slate-500/20 text-slate-200' }}">
                                    {{ $appointment->activo ? 'Sí' : 'No' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex justify-end gap-2">
                                    <flux:button type="button" size="sm" variant="ghost" wire:click="selectAppointment({{ $appointment->id }})">Editar</flux:button>
                                    <flux:button
                                        type="button"
                                        size="sm"
                                        variant="ghost"
                                        onclick="if (! confirm('¿Eliminar esta cita?')) return; $wire.delete({{ $appointment->id }})"
                                    >
                                        Eliminar
                                    </flux:button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-4 py-6 text-slate-400" colspan="6">No hay citas para mostrar todavía.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $appointments->links() }}
        </div>
    </div>
</div>
