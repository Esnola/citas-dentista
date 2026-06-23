<div class="grid gap-6">
    @if (session('status'))
        <div class="rounded-2xl border border-emerald-400/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
            {{ session('status') }}
        </div>
    @endif

    <div class="rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold">
                    {{ $selectedClient ? 'Citas de '.$selectedClient->full_name : 'Citas registradas' }}
                </h2>
                <p class="mt-2 text-sm text-slate-300">
                    @if ($selectedClient)
                        Listado de citas de {{ $selectedClient->telefono }}.
                    @else
                        Listado de citas enlazadas con clientes y estado de envío.
                    @endif
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <div class="rounded-2xl border border-white/10 bg-slate-900/60 px-4 py-3 text-sm text-slate-300">
                    {{ $appointments->total() }} resultados
                </div>
                @if ($selectedClient)
                    <flux:button href="{{ route('appointments.index') }}">Ver todas</flux:button>
                @endif
                <flux:button class="action-add" href="{{ route('appointments.create', $selectedClient ? ['client' => $selectedClient->id] : []) }}">Nueva cita</flux:button>
            </div>
        </div>

        <div class="mt-4 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <flux:field>
                <flux:label>Nombre</flux:label>
                <flux:input wire:model.live.debounce.300ms="filter_nombre" placeholder="Filtrar por nombre" />
            </flux:field>

            <flux:field>
                <flux:label>Apellidos</flux:label>
                <flux:input wire:model.live.debounce.300ms="filter_apellidos" placeholder="Filtrar por apellidos" />
            </flux:field>

            <flux:field>
                <flux:label>Enviado</flux:label>
                <label class="inline-flex cursor-pointer items-center gap-2 rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-3">
                    <input class="peer sr-only" type="checkbox" wire:model.live="filter_enviado">
                    <span class="h-5 w-9 rounded-full bg-slate-700 transition after:block after:h-4 after:w-4 after:translate-x-0.5 after:translate-y-0.5 after:rounded-full after:bg-white after:transition peer-checked:bg-emerald-500 peer-checked:after:translate-x-4 peer-focus-visible:ring-2 peer-focus-visible:ring-emerald-300"></span>
                    <span class="text-sm text-slate-200">Solo enviadas</span>
                </label>
            </flux:field>

            <flux:field>
                <flux:label>Activo</flux:label>
                <label class="inline-flex cursor-pointer items-center gap-2 rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-3">
                    <input class="peer sr-only" type="checkbox" wire:model.live="filter_activo">
                    <span class="h-5 w-9 rounded-full bg-slate-700 transition after:block after:h-4 after:w-4 after:translate-x-0.5 after:translate-y-0.5 after:rounded-full after:bg-white after:transition peer-checked:bg-emerald-500 peer-checked:after:translate-x-4 peer-focus-visible:ring-2 peer-focus-visible:ring-emerald-300"></span>
                    <span class="text-sm text-slate-200">Solo activas</span>
                </label>
            </flux:field>

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
                        <th class="px-4 py-3">Activo</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10 bg-slate-950/40">
                    @forelse ($appointments as $appointment)
                        @php($canChange = $appointment->canBeChanged())
                        <tr
                            wire:key="appointment-{{ $appointment->id }}"
                            class="{{ $canChange ? '' : 'bg-slate-900/50 text-slate-400' }}"
                        >
                            <td class="px-4 py-3">
                                <a
                                    href="{{ route('appointments.index', ['client' => $appointment->client_id]) }}"
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
                                @if ($canChange)
                                    <label class="inline-flex cursor-pointer items-center gap-2">
                                        <input
                                            class="peer sr-only"
                                            type="checkbox"
                                            @checked($appointment->activo)
                                            wire:change="updateActiveStatus({{ $appointment->id }}, $event.target.checked)"
                                        >
                                        <span class="h-5 w-9 rounded-full bg-slate-700 transition after:block after:h-4 after:w-4 after:translate-x-0.5 after:translate-y-0.5 after:rounded-full after:bg-white after:transition peer-checked:bg-emerald-500 peer-checked:after:translate-x-4 peer-focus-visible:ring-2 peer-focus-visible:ring-emerald-300"></span>
                                        <span class="text-xs text-slate-300">{{ $appointment->activo ? 'Sí' : 'No' }}</span>
                                    </label>
                                @else
                                    <span class="rounded-full px-2.5 py-1 text-xs font-medium {{ $appointment->activo ? 'bg-sky-500/20 text-sky-200' : 'bg-slate-500/20 text-slate-200' }}">
                                        {{ $appointment->activo ? 'Sí' : 'No' }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex justify-end gap-2">
                                    @if ($canChange)
                                        <a
                                            class="action-edit inline-flex h-8 w-8 items-center justify-center rounded-md"
                                            href="{{ route('appointments.edit', $appointment) }}"
                                            aria-label="Editar cita"
                                            title="Editar cita"
                                        >
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                <path d="M12 20h9" />
                                                <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z" />
                                            </svg>
                                        </a>
                                    @endif
                                    <button
                                        class="action-delete inline-flex h-8 w-8 items-center justify-center rounded-md"
                                        type="button"
                                        wire:click="confirmDelete({{ $appointment->id }})"
                                        aria-label="Eliminar cita"
                                        title="Eliminar cita"
                                    >
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="M3 6h18" />
                                            <path d="M8 6V4h8v2" />
                                            <path d="M19 6l-1 14H6L5 6" />
                                            <path d="M10 11v6" />
                                            <path d="M14 11v6" />
                                        </svg>
                                    </button>
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
                    <flux:button type="button" wire:click="cancelDelete">Cancelar</flux:button>
                    <flux:button class="action-delete" type="button" wire:click="deleteConfirmed">Eliminar</flux:button>
                </div>
            </div>
        </div>
    @endif
</div>
