<div class="grid gap-6">
    @if (session('status'))
        <div class="rounded-2xl border border-emerald-400/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
            {{ session('status') }}
        </div>
    @endif

    <div class="rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="flex  gap-6 p-4">
                   <x-iconos.calendar />
                <h2 class="text-xl font-semibold">
                    {{ $selectedClient ?  $selectedClient->full_name : 'Citas registradas' }}
                </h2>
                <div class="rounded-2xl border border-white/10 bg-slate-900/60 px-4 py-3 text-sm text-slate-300">
                    {{ $appointments->total() }} cita{{ $appointments->total()>1 ? 's' : '' }}
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-6">
                @if ($selectedClient)
                    <x-botones.accion href="{{ route('appointments.index') }}" icono="calendar"
                    >Todas las citas</x-botones.accion>
                @endif
                <x-botones.accion variant="add" icono="plus" href="{{ route('appointments.create', $selectedClient ? ['client' => $selectedClient->id] : []) }}">Nueva cita</x-botones.accion>

            </div>
        </div>

        <div class="mt-4 flex items-center gap-4">
            <flux:field>
                <flux:label>Nombre</flux:label>
                <x-formularios.input wire:model.live.debounce.300ms="filter_nombre" placeholder="Filtrar por nombre" />
            </flux:field>

            <flux:field>
                <flux:label>Apellidos</flux:label>
                <x-formularios.input wire:model.live.debounce.300ms="filter_apellidos" placeholder="Filtrar por apellidos" />
            </flux:field>

                <div class="flex flex-col items-center justify-center gap-2">
                    <flux:label class="text-[14px] font-bold">Notificaciones</flux:label>
                <div class="flex items-center jusitfy-center ml-6 gap-4">
                <flux:field class="flex flex-col">
                    <flux:label>Enviadas</flux:label>
                    <x-formularios.toggle wire:model.live="filter_enviado" :disabled="$filter_activo || $filter_entregado" :locked="$filter_activo || $filter_entregado"/>
                </flux:field>

                <flux:field class="flex flex-col">
                    <flux:label>Entregadas</flux:label>
                    <x-formularios.toggle wire:model.live="filter_entregado" :disabled="$filter_enviado || $filter_activo" :locked="$filter_enviado || $filter_activo"/>
                </flux:field>

                <flux:field class="flex flex-col">
                    <flux:label>No pendientes</flux:label>
                    <x-formularios.toggle wire:model.live="filter_activo" :disabled="$filter_enviado || $filter_entregado" :locked="$filter_enviado || $filter_entregado"/>
                </flux:field>
            </div>
            </div>
        </div>

        <div class="mt-4 overflow-hidden rounded-2xl border border-white/10">
            <table class="min-w-full divide-y divide-white/10 text-left text-sm">
                <thead class="bg-slate-900/70 text-slate-300">
                    <tr>
                        <th class="px-4 py-3">
                            <button type="button" class="inline-flex cursor-pointer items-center gap-1 font-semibold text-slate-200 hover:text-white" wire:click="sortByColumn('cliente')">
                                Cliente
                                <span class="text-xs text-slate-400">
                                    @if ($sort_by === 'cliente')
                                        {{ $sort_direction === 'asc' ? '↑' : '↓' }}
                                    @else
                                        ↕
                                    @endif
                                </span>
                            </button>
                        </th>
                        <th class="px-4 py-3">
                            <button type="button" class="inline-flex cursor-pointer items-center gap-1 font-semibold text-slate-200 hover:text-white" wire:click="sortByColumn('fecha')">
                                Fecha
                                <span class="text-xs text-slate-400">
                                    @if ($sort_by === 'fecha')
                                        {{ $sort_direction === 'asc' ? '↑' : '↓' }}
                                    @else
                                        ↕
                                    @endif
                                </span>
                            </button>
                        </th>
                        <th class="px-4 py-3">Hora</th>
                        <th class="px-4 py-3">Enviado</th>
                        <th class="px-4 py-3">Entregado</th>
                        <th class="px-4 py-3">Pendiente</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10 bg-slate-950/40">
                    @forelse ($appointments as $appointment)
                        @php
                            $canChange = $appointment->canBeChanged();
                            $canSendNow = $selectedClient && ! $appointment->enviado && $appointment->activo && $appointment->isFuture();
                            $editUrl = route('appointments.edit', $appointment);
                            $rowUrl = $selectedClient
                                ? $editUrl
                                : route('appointments.index', ['client' => $appointment->client_id]);
                        @endphp
                        <tr
                            wire:key="appointment-{{ $appointment->id }}"
                            role="link"
                            tabindex="0"
                            onclick="window.location='{{ $rowUrl }}'"
                            onkeydown="if (event.key === 'Enter' || event.key === ' ') { event.preventDefault(); window.location='{{ $rowUrl }}'; }"
                            class="cursor-pointer transition-colors hover:bg-white/5 {{ $canChange ? '' : 'bg-slate-900/50 text-slate-400' }}"
                        >
                            <td class="px-4 py-3">
                                <a
                                    href="{{ $rowUrl }}"
                                    class="font-medium {{ $canChange ? 'text-emerald-300 hover:text-emerald-200' : 'text-slate-400 hover:text-slate-300' }}"
                                >
                                    {{ $appointment->client?->nombre }} {{ $appointment->client?->apellidos }}
                                </a>
                                <p class="text-xs text-slate-400">{{ $appointment->client?->telefono }}</p>
                            </td>
                            <td class="px-4 py-3">{{ $appointment->fecha?->format('d/m/Y') }}</td>
                            <td class="px-4 py-3">{{ $appointment->hora }}</td>
                            <td class="px-4 py-3">
                                <span class="rounded-full px-2.5 py-1 text-xs font-medium {{ $appointment->enviado ? 'bg-emerald-500/20 text-emerald-200' : 'bg-slate-500/20 text-slate-200' }}">
                                    {{ $appointment->enviado ? 'Sí' : 'No' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="rounded-full px-2.5 py-1 text-xs font-medium {{ $appointment->entregado ? 'bg-sky-500/20 text-sky-200' : 'bg-slate-500/20 text-slate-200' }}">
                                    {{ $appointment->entregado ? 'Sí' : 'No' }}
                                </span>
                            </td>
                            <td class="px-4 py-3" onclick="event.stopPropagation()">
                                @if ($canChange)
                                    <x-formularios.toggle
                                        :estado="$appointment->activo ? 'Sí' : 'No'"
                                        :checked="$appointment->activo"
                                        wire:change="updateActiveStatus({{ $appointment->id }}, $event.target.checked)"
                                    />
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right" onclick="event.stopPropagation()">
                                <div class="flex justify-end gap-2">
                                    @if ($canSendNow)
                                        <x-botones.accion
                                            variant="add"
                                            size="sm"
                                            type="button"
                                            wire:click="sendNow({{ $appointment->id }})"
                                            wire:loading.attr="disabled"
                                            wire:target="sendNow({{ $appointment->id }})"
                                        >
                                            Enviar ya
                                        </x-botones.accion>
                                    @endif
                                    @if ($canChange)
                                        <x-botones.accion
                                            variant="edit"
                                            size="icon"
                                            icono="edit"
                                            href="{{ $editUrl }}"
                                            aria-label="Editar cita"
                                            title="Editar cita"
                                        />
                                    @endif
                                    <x-botones.accion
                                        variant="delete"
                                        size="icon"
                                        icono="delete"
                                        type="button"
                                        wire:click="confirmDelete({{ $appointment->id }})"
                                        aria-label="Eliminar cita"
                                        title="Eliminar cita"
                                    />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-4 py-6 text-slate-400" colspan="7">No hay citas para mostrar todavía.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $appointments->links() }}
        </div>
    </div>

    @if ($appointmentPendingDeletion)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/80 px-4 py-6">
            <div class="w-full max-w-md rounded-3xl border border-white/10 bg-slate-900 p-6 shadow-2xl">
                <h3 class="text-lg font-semibold">Eliminar cita</h3>
                <p class="mt-3 text-sm text-slate-300">
                    ¿Seguro que quieres eliminar la cita de
                    <span class="font-medium text-white">{{ $appointmentPendingDeletion->client?->full_name }}</span>
                    del {{ $appointmentPendingDeletion->fecha?->format('d/m/Y') }} a las {{ $appointmentPendingDeletion->hora }}?
                </p>
                <p class="mt-2 text-sm text-slate-400">Esta acción no se puede deshacer.</p>

                <div class="mt-6 flex flex-wrap justify-end gap-2">
                    <x-botones.accion type="button" wire:click="cancelDelete">Cancelar</x-botones.accion>
                    <x-botones.accion variant="delete" icono="delete" type="button" wire:click="deleteConfirmed">Eliminar</x-botones.accion>
                </div>
            </div>
        </div>
    @endif
</div>
