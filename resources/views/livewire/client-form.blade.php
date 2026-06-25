<div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_420px]">
    @if (session('status'))
        <div class="xl:col-span-2 rounded-2xl border border-emerald-400/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
            {{ session('status') }}
        </div>
    @endif

        <div class="rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur">
            <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Citas</p>
            @if ($selectedClient)

                <p class="mt-2 font-medium">{{ $selectedClient->nombre }} {{ $selectedClient->apellidos }}
                    <span class="ml-4 inline-flex items-center rounded-full bg-green-900/20 text-xs font-medium text-green-400 inset-ring inset-ring-green-500/20 px-2.5 py-1 ">
                    <flux:icon.phone class="h-3.5 w-3.5" /> &nbsp {{ $selectedClient->telefono }}
            </span></p>
                <p class="mt-1 text-sm text-slate-300">Alta: {{ $selectedClient->created_at?->format('d/m/Y H:i') }}</p>

                <div class="mt-5 border-t border-white/10 pt-5">
                    <div class="flex items-center justify-between gap-3">
                        <x-botones.accion variant="add" size="sm" icono="plus" href="{{ route('appointments.create', ['client' => $selectedClient->id]) }}">
                            Nueva cita
                        </x-botones.accion>
                    </div>

                    <div class="mt-3 grid gap-2">
                        @forelse ($selectedClient->appointments as $appointment)
                            @php
                                $isFutureAppointment = $appointment->isFuture();
                                $canSendAppointmentNow = ! $appointment->enviado && $appointment->activo && $isFutureAppointment;
                                $editUrl = route('appointments.edit', $appointment);
                                $badgeBaseClasses = 'inline-flex items-center rounded-full px-2 py-1 text-xs font-medium inset-ring';

                                $appointmentStatus = match (true) {
                                    $appointment->entregado => [
                                        'label' => 'Entregado',
                                        'classes' => "{$badgeBaseClasses} bg-sky-400/10 text-sky-400 inset-ring-sky-500/20",
                                        'icono' => 'check-circle',
                                        'canChange' => false,
                                    ],
                                    $appointment->enviado => [
                                        'label' => 'Enviado',
                                        'classes' => "{$badgeBaseClasses} bg-green-400/10 text-green-400 inset-ring-green-500/20",
                                        'icono' => 'check-circle',
                                        'canChange' => false,
                                    ],
                                    ! $isFutureAppointment => [
                                        'label' => 'No Enviado!',
                                        'classes' => "{$badgeBaseClasses} bg-red-400/10 text-red-400 inset-ring-red-400/20",
                                        'icono' => 'exclamation-triangle',
                                        'canChange' => false,
                                    ],
                                    $appointment->activo => [
                                        'label' => 'Pendiente',
                                        'classes' => "{$badgeBaseClasses} bg-yellow-400/10 text-yellow-400 inset-ring-yellow-400/20",
                                        'icono' => 'clock',
                                        'canChange' => true,
                                    ],
                                    default => [
                                        'label' => 'Inactivo',
                                        'classes' => "{$badgeBaseClasses} bg-blue-400/10 text-blue-400 inset-ring-blue-400/20",
                                        'icono' =>'exclamation-circle',
                                        'canChange' => true,
                                    ],
                                };

                                $canChangeAppointment = $appointmentStatus['canChange'];

                            @endphp
                            <div
                                wire:key="client-form-appointment-{{ $appointment->id }}"
                                role="link"
                                tabindex="0"
                                onclick="window.location='{{ $editUrl }}'"
                                onkeydown="if (event.key === 'Enter' || event.key === ' ') { event.preventDefault(); window.location='{{ $editUrl }}'; }"
                                class="cursor-pointer rounded-2xl border border-white/10 bg-slate-950/40 p-3 transition-colors hover:bg-white/5 {{ $canChangeAppointment ? '' : 'opacity-70' }}"
                            >
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex gap-2 items-center">
                                        <p class="font-medium">{{ $appointment->fecha?->format('d/m/Y') }} {{ $appointment->hora }}</p>
                                        <p class="mt-1 {{ $appointmentStatus['classes'] }}">
                                            <x-dynamic-component
                                                :component="'flux::icon.' . $appointmentStatus['icono']"
                                                class="mr-1 h-4 w-4"
                                            /> {{ $appointmentStatus['label'] }}
                                        </p>
                                    </div>
                                    <div class="flex flex-wrap justify-end gap-2" onclick="event.stopPropagation()">
                                        @if ($canSendAppointmentNow)
                                            <x-botones.accion
                                                variant="add"
                                                size="sm"
                                                type="button"
                                                wire:click="sendAppointmentNow({{ $appointment->id }})"
                                                wire:loading.attr="disabled"
                                                wire:target="sendAppointmentNow({{ $appointment->id }})"
                                            >
                                                Enviar ya
                                            </x-botones.accion>
                                        @endif
                                        @if ($canChangeAppointment)
                                            <x-formularios.toggle
                                                :estado="$appointment->activo ? 'Activo' : 'Inactivo'"
                                                :checked="$appointment->activo"
                                                wire:change="updateAppointmentActiveStatus({{ $appointment->id }}, $event.target.checked)"
                                            />
                                        @else
                                        <x-botones.accion
                                            variant="delete"
                                            size="icon"
                                            icono="delete"
                                            type="button"
                                            wire:click="deleteAppointment({{ $appointment->id }})"
                                            onclick="return confirm('¿Eliminar esta cita?')"
                                            aria-label="Eliminar cita"
                                            title="Eliminar cita"
                                        />
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-slate-400">Este cliente todavía no tiene citas registradas.</p>
                        @endforelse
                    </div>
                </div>
            @else
                <p class="mt-2 text-sm text-slate-300">Guarda el cliente para ver su ficha y sus citas.</p>
            @endif
        </div>
    <div class="rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold">{{ $selectedClient ? 'Editar cliente' : 'Crear cliente' }}</h2>
                <p class="mt-2 text-sm text-slate-300">Gestiona los datos básicos de la ficha del cliente.</p>
            </div>
            <x-botones.accion variant="indigo" size="sm" icono="back" href="{{ route('clients.list') }}">Volver al listado</x-botones.accion>
        </div>

        <form class="mt-6 grid gap-4" wire:submit="save">
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
                <x-botones.accion variant="indigo" size="sm" href="{{ route('clients.list') }}">Cancelar</x-botones.accion>
            </div>
        </form>
    </div>

</div>
