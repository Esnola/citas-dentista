<div class="mx-auto max-w-6xl ml-7.5 mt-25 grid gap-6">
  <div class="rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur">
    {{-- Header --}}
    <div class="flex flex-wrap items-start justify-between gap-4">
      <div class="flex items-center gap-4">
        <x-iconos.calendar/>
        <div>
          <h2 class="text-md">{{ $selectedAppointment ? 'Gestión cita de:' : 'Nueva cita para:' }} <br/>
            <span class=" font-semibold">{{ $selectedClient ? $selectedClient->nombre . ' ' . $selectedClient->apellidos : 'Cliente no seleccionado' }}</span>
          </h2>

        </div>
      </div>
      <x-botones.icono-buton
              color="indigo"
              icon="back"
              especial="size-5"
              label="Volver"
              texto="Volver"
              onclick="history.back()"
      />
    </div>

    {{-- Warning banner --}}
    @if($selectedAppointment && ! $canChangeAppointment && ! $showReturnAfterImmediateSend)
      <div class="mt-6 flex items-center gap-3 rounded-2xl border border-amber-400/30 bg-amber-500/10 px-5 py-3.5 text-sm text-amber-100">
        <x-iconos.alert clase="size-5 shrink-0"/>
        Esta cita ya fue enviada o pertenece al pasado. No se puede modificar; solo se puede eliminar desde el listado.
      </div>
    @endif

    {{-- Client search (create mode) --}}
    @if(! $isEditing && ! $hideClientSearch)
      <div class="mt-8">
        <h3 class="text-sm font-semibold text-slate-200 mb-3">Buscar cliente</h3>
        <div class="flex items-start gap-4">
          <div class="flex gap-3">
            <flux:field>
              <flux:label>Nombre</flux:label>
              <x-formularios.input wire:model.live.debounce.300ms="filter_nombre" placeholder="Nombre"
                                   :disabled="! $canChangeAppointment"/>
            </flux:field>
            <flux:field>
              <flux:label>Apellidos</flux:label>
              <x-formularios.input wire:model.live.debounce.300ms="filter_apellidos"
                                   placeholder="Apellidos" :disabled="! $canChangeAppointment"/>
            </flux:field>
            <flux:field>
              <flux:label>Teléfono</flux:label>
              <x-formularios.input wire:model.live.debounce.300ms="filter_telefono"
                                   placeholder="Teléfono" :disabled="! $canChangeAppointment"/>
            </flux:field>
          </div>
        </div>

        @if ($hasMoreThanTenClientResults)
          <div class="mt-3 inline-flex gap-2 items-center rounded-full border border-yellow-100/80 bg-yellow-300/10 px-4 py-1.5 text-xs font-medium text-yellow-100">
            <x-iconos.alert clase="size-4"/>
            Hay más de 10 resultados, afina la búsqueda.
          </div>
        @endif

        @if ($hasClientSearch)
          <div class="mt-4 grid grid-cols-3 gap-2">
            @forelse ($clients as $client)
              <button
                      type="button"
                      wire:key="appointment-form-client-{{ $client->id }}"
                      wire:click="selectClient({{ $client->id }})"
                      @disabled(! $canChangeAppointment)
                      class="rounded-2xl border p-4 text-left transition-all duration-200 {{ $selectedClient?->id === $client->id ? 'border-emerald-400/60 bg-emerald-500/10 shadow-lg shadow-emerald-500/5' : 'border-amber-300/30 bg-slate-950/40 hover:border-blue-400/40 hover:bg-blue-500/10' }}"
              >
                <span class="block font-medium text-sm">{{ $client->nombre }} {{ $client->apellidos }}</span>
              </button>
            @empty
              <p class="col-span-5 rounded-2xl border border-white/10 bg-slate-950/40 p-4 text-sm text-slate-400 text-center">
                No hay coincidencias para esa búsqueda.
              </p>
            @endforelse
          </div>
        @else
          <p class="mt-4 rounded-2xl border border-white/10 bg-slate-950/40 p-4 text-sm text-slate-400 text-center">
            Las coincidencias aparecerán aquí cuando escribas al menos un carácter.
          </p>
        @endif
      </div>
    @endif

    {{-- Appointment form --}}
    @if ($selectedClient)
      <div class="mt-8 grid gap-6 border-t border-white/5 pt-8 xl:grid-cols-[minmax(0,1fr)_22rem] xl:items-start">
        <form wire:submit="save" class="min-w-0">
          <flux:error name="selectedClientId"/>

          <div class="grid grid-cols-12 gap-6">

            {{-- Date & Time card --}}
            <div class="col-span-5 rounded-2xl border border-white/10 bg-slate-900/50 p-6">
              <div class="flex items-center gap-2 mb-5">
                <x-iconos.calendar clase="size-4 text-emerald-400"/>
                <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">Fecha y hora</p>
              </div>
              <div class="space-y-4">
                <flux:field>
                  <flux:label>Fecha</flux:label>
                  <x-formularios.input
                          wire:model.live="fecha"
                          type="date"
                          :min="$minimumSelectableDate"
                          data-no-sundays
                          :disabled="! $canChangeAppointment"
                  />
                  <flux:error name="fecha"/>
                  @if ($hasAppointmentDateConflict)
                    <p class="mt-2 text-xs font-medium text-amber-200">Este cliente ya tiene una cita ese día.</p>
                  @endif
                </flux:field>
                <flux:field>
                  <flux:label>Hora</flux:label>
                  <x-formularios.input
                          wire:model.live="hora"
                          data-time-picker
                          inputmode="numeric"
                          maxlength="5"
                          pattern="(?:[01][0-9]|2[0-3]):[0-5][0-9]"
                          placeholder="--:--"
                          :disabled="! $canChangeAppointment"
                  />
                  <flux:error name="hora"/>
                </flux:field>
              </div>
            </div>

            {{-- Client info card --}}
            <div class="col-span-7 rounded-2xl border border-white/10 bg-slate-900/50 p-6">
              <div class="flex items-center jusitfy-center gap-8">
                <div class="flex items-center gap-2 mb-5">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                       stroke="currentColor" class="size-4 text-sky-400">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                  </svg>
                  <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">Cliente</p>
                </div>
                <div class="flex items-center gap-2 mb-5">
                  <x-iconos.telefono-mesa clase="size-6 text-green-500/50"/>
                  <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">{{ $selectedClient->telefono }}</p>
                </div>
              </div>
              <p class="text-lg font-semibold text-white">{{ $selectedClient->nombre }} {{ $selectedClient->apellidos }}</p>

              <div class="mt-5 space-y-4 border-t border-white/5 pt-5">
                @if (! $selectedAppointment)
                  <div class="flex items-start justify-between gap-4 rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-3">
                    <div>
                      <p class="text-sm font-medium text-slate-100">Whatsapp al crear</p>
                      <p class="mt-1 text-xs text-slate-400">Envía el primer mensaje al guardar la cita.</p>
                    </div>
                    <x-formularios.toggle
                            wire:model="sendImmediately"
                            :disabled="! $canChangeAppointment"
                            :locked="! $canChangeAppointment"
                    />
                  </div>
                  <flux:error name="sendImmediately"/>
                @endif

                @if ($selectedAppointment)
                  <div class="flex items-start justify-between gap-4 rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-3">
                    <div>
                      <p class="text-sm font-medium text-slate-100">Enviar ya</p>
                      <p class="mt-1 text-xs text-slate-400">Envía el WhatsApp de cambio al guardar la cita.</p>
                    </div>
                    <x-formularios.toggle
                            wire:model="sendImmediately"
                            :disabled="! $canChangeAppointment"
                            :locked="! $canChangeAppointment"
                    />
                  </div>
                  <flux:error name="sendImmediately"/>
                @endif

                <div class="flex items-start justify-between gap-4 rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-3">
                  <div>
                    <p class="text-sm font-medium text-slate-100">Comunicaciones</p>
                    <p class="mt-1 text-xs text-slate-400">Permite recordatorios y envíos manuales para esta cita.</p>
                  </div>
                  <x-formularios.toggle
                          wire:model="activo"
                          :disabled="! $canChangeAppointment"
                          :locked="! $canChangeAppointment"
                  />
                </div>
                <flux:error name="activo"/>
              </div>
            </div>
          </div>

          {{-- Actions --}}
          <div class="p-18 flex flex-wrap items-center justify-center gap-3">
            @if ($showReturnAfterImmediateSend)
              <x-botones.icono-buton
                      icon="volver"
                      label="Citas de {{ $selectedClient->nombre }}"
                      texto="Citas de {{ $selectedClient->nombre }}"
                      onclick="window.location.href='{{ $returnUrl }}'"
              />
            @else
              <x-botones.icono-buton
                      color="sky"
                      type="submit"
                      icon="disquete"
                      especial="size-6"
                      label="{{ $selectedAppointment ? 'Guardar cambios' : 'Crear cita' }}"
                      texto="{{ $selectedAppointment ? 'Guardar cambios' : 'Crear cita' }}"
                      :disabled="! $canSaveAppointment"
              />
              <x-botones.icono-buton
                      color="gray"
                      icon="user-cita"
                      especial="size-6"
                      label="Citas de {{ $selectedClient->nombre }}"
                      texto="Citas de {{ $selectedClient->nombre }}"
                      onclick="window.location.href='{{ $returnUrl }}'"
              />
            @endif
          </div>
        </form>

        <aside class="overflow-hidden rounded-2xl border border-white/10 bg-slate-900/50">
          <header class="flex flex-wrap items-center justify-between gap-3 border-b border-white/10 px-5 py-4">
            <div>
              <h3 class="text-sm font-semibold text-slate-100">Agenda de citas</h3>
              <p class="mt-1 text-xs text-slate-400">Fecha y hora de las citas de este cliente.</p>
            </div>
            <span class="inline-flex items-center rounded-full bg-emerald-400/10 px-3 py-1 text-xs font-semibold text-emerald-300 ring-1 ring-inset ring-emerald-400/20">
                {{ $appointmentHistory->count() }} {{ $appointmentHistory->count() === 1 ? 'cita' : 'citas' }}
              </span>
          </header>

          <ol class="divide-y divide-white/10">
            @forelse ($appointmentHistory as $appointment)
              <li wire:key="appointment-create-history-{{ $appointment->id }}"
                  class="flex flex-wrap items-center justify-between gap-3 px-5 py-3 text-sm text-slate-400">
                <p>{{ ucfirst($appointment->fecha->translatedFormat('l, d/m/Y')) }}</p>
                <p>{{ Str::substr($appointment->hora, 0, 5) }}</p>
              </li>
            @empty
              <li class="px-5 py-8 text-center text-sm text-slate-300">Este cliente no tiene citas registradas.</li>
            @endforelse
          </ol>
        </aside>
      </div>
    @endif
  </div>
</div>

@vite(['resources/js/data-picker.js'])
