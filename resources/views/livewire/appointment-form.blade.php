<div class="rounded-3xl border border-white/10 p-12">
    <div class="rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur">

        @if(! $isEditing)
        <div class="flex items-center justify-between">
            <div class="">
                <h2 class="text-xl font-semibold">Buscar cliente</h2>
                <p class="mt-2 text-sm text-slate-300">
                    Escribe al menos un carácter en cualquier campo para ver coincidencias.
                </p>
            </div>
            <x-botones.accion variant="edit" href="{{ route('appointments.index') }}" class="mr-12">
                <x-iconos.back-door />
                Volver al listado</x-botones.accion>
        </div>
        <div class="flex mt-6 items-start gap-4">
            <div class="mb-6">
                <flux:field>
                    <flux:label>Nombre</flux:label>
                    <x-formularios.input wire:model.live.debounce.300ms="filter_nombre" placeholder="Buscar por nombre"
                                         :disabled="! $canChangeAppointment"/>
                </flux:field>

                <flux:field>
                    <flux:label>Apellidos</flux:label>
                    <x-formularios.input wire:model.live.debounce.300ms="filter_apellidos"
                                         placeholder="Buscar por apellidos" :disabled="! $canChangeAppointment"/>
                </flux:field>

                <flux:field>
                    <flux:label>Teléfono</flux:label>
                    <x-formularios.input wire:model.live.debounce.300ms="filter_telefono"
                                         placeholder="Buscar por teléfono" :disabled="! $canChangeAppointment"/>
                </flux:field>
            </div>
            @if ($hasClientSearch)
                <div class="mt-6 grid grid-cols-5 gap-2 w-full ">
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
            @else
                <p class="mt-8 rounded-2xl border border-white/10 bg-slate-950/40 p-4 text-sm text-slate-400 w-full">
                    Las coincidencias aparecerán aquí cuando escribas al menos un carácter.
                </p>
            @endif
        </div>
        @endif
        @if (session('status'))
            <div
                class="xl:col-span-2 rounded-2xl border border-emerald-400/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
                {{ session('status') }}
            </div>
        @endif
        @if ($hasMoreThanTenClientResults)
            <div class="mt-4 inline-flex gap-4 items-center rounded-full border border-yellow-100/80 bg-yellow-300/10 px-6 py-2 text-sm font-medium text-yellow-100 ">
              <x-iconos.alert clase="size-8 mr-1.5" />
                Hay más de 10 resultados, afina la búsqueda.
            </div>
        @endif
        @if ($selectedClient)
            <div data-gestion>
                <div class="mt-12 flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-semibold">{{ $selectedAppointment ? 'Editar cita' : 'Gestión cita' }}</h2>
                        <p class="mt-2 text-sm text-slate-300">Gestiona fecha y hora de la cita.</p>
                    </div>
                </div>
                @if($selectedAppointment && ! $canChangeAppointment && ! $showReturnAfterImmediateSend)
                    <div class="mt-6 rounded-2xl border border-amber-400/30 bg-amber-500/10 px-4 py-3 text-sm text-amber-100">
                        Esta cita ya fue enviada o pertenece al pasado. No se puede modificar; solo se puede eliminar desde el
                        listado.
                    </div>
                @endif

                <form class="mt-6 grid gap-12" wire:submit="save">
                    <flux:error name="selectedClientId"/>
                    <div class="grid grid-cols-8 gap-4">
                        <flux:field>
                            <flux:label>Fecha</flux:label>
                            <x-formularios.input
                                wire:model="fecha"
                                type="date"
                                :min="$minimumSelectableDate"
                                data-no-sundays
                                :disabled="! $canChangeAppointment"
                            />
                            <flux:error name="fecha"/>
                        </flux:field>
                        <flux:field >
                            <flux:label>Hora</flux:label>
                            <x-formularios.input
                                wire:model="hora"
                                data-time-picker
                                readonly
                                placeholder="--:--"
                                :disabled="! $canChangeAppointment"
                            />
                            <flux:error name="hora"/>
                        </flux:field>
                        <div class="mt-9 col-span-6 rounded-2xl border border-white/10 bg-slate-950/40 p-4">
                            <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Cliente seleccionado</p>
                            <p class="mt-2 font-medium">{{ $selectedClient->nombre }} {{ $selectedClient->apellidos }}</p>
                            <p class="mt-1 text-sm text-slate-300">{{ $selectedClient->telefono }}</p>
                            <p class="mt-1 text-sm text-slate-300">
                                Alta: {{ $selectedClient->created_at?->format('d/m/Y H:i') }}</p>
                            @if (! $selectedAppointment)
                                <div class="mt-4">
                                    <x-formularios.toggle
                                        wire:model="sendImmediately"
                                        texto="Enviar WhatsApp ahora"
                                        variant="sky"
                                        :disabled="! $canChangeAppointment"
                                        :locked="! $canChangeAppointment"
                                    />
                                    <flux:error name="sendImmediately"/>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        @if ($showReturnAfterImmediateSend)
                            <x-botones.accion variant="indigo" icono="back" href="{{ $returnUrl }}">Volver</x-botones.accion>
                        @else
                            @if ($selectedAppointment)
                                <x-botones.accion
                                    variant="indigo"
                                    icono="check"
                                    type="button"
                                    wire:click="sendNow"
                                    wire:loading.attr="disabled"
                                    :disabled="! $canSendAppointmentNow"
                                >
                                    Enviar ya
                                </x-botones.accion>
                            @endif
                            <x-botones.accion variant="add" icono="check" type="submit"
                                              :disabled="! $selectedClient || ! $canChangeAppointment">
                                {{ $selectedAppointment ? 'Guardar cambios' : 'Crear cita' }}
                            </x-botones.accion>
                            <x-botones.accion href="{{ route('appointments.index') }}">Cancelar</x-botones.accion>
                        @endif
                    </div>
                </form>
            </div>
        @endif
    </div>
</div>
</div>
