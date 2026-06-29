<div class="grid gap-6">
  <div class="rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur">
    <div class="flex flex-wrap items-start justify-between gap-4">
      <div>
        <div class="flex gap-6 ">
          <x-iconos.calendar/>
          <h2 class="text-xl font-semibold">
            {{ $sentOnly ? 'Citas enviadas' : ($selectedClient ?  $selectedClient->full_name : 'Citas registradas') }}
          </h2>
          <h3 class="rounded-2xl border border-white/10 bg-slate-900/60 px-4 py-3 text-sm text-slate-300">
            {{ $appointmentsCount }} cita{{ $appointmentsCount > 1 ? 's' : '' }}
          </h3>
        </div>
        <div>
          @if ($deliveryStatusesSyncedAt)
            <span class="text-xs font-medium text-slate-400">Sincronizado: {{ $deliveryStatusesSyncedAt }}</span>
          @endif
        </div>
      </div>
      <div class="flex flex-wrap items-center gap-6">
        <x-botones.icono-buton
                color="indigo"
                icon="reload"
                label="Actualizar Datos"
                texto="Actualizar Datos"
                x-on:click="localStorage.setItem('autoSyncAfterSend', '1')"
                wire:click="syncDeliveryStatuses"
                wire:loading.attr="disabled"
                wire:target="syncDeliveryStatuses"
        />

        @if ($showAppointmentNavigation)
          <div class="contents" data-appointment-navigation="{{ $sentOnly ? 'all' : 'sent' }}">
            @if ($sentOnly)
       {{--       <x-botones.accion href="{{ route('appointments.index') }}">
                <x-iconos.calendar clase="size-4"/>
                Todas las citas
              </x-botones.accion>--}}
              <x-botones.icono-buton
                      icon="calendar"
                      label="Todas las citas"
                      texto="Todas las citas"
                      onclick="window.location.href='{{ route('appointments.index')}}'"
              />

            @else
          {{--    <x-botones.accion href="{{ route('appointments.sent') }}">
                <x-iconos.calendario-filtro clase="size-4"/>
                Citas enviadas
              </x-botones.accion>--}}
              <x-botones.icono-buton
                      icon="calendario-filtro"
                      label="Citas enviadas"
                      texto="Citas enviadas"
                      onclick="window.location.href='{{ route('appointments.sent')}}'"
              />
            @endif
          </div>
        @endif
{{--        <x-botones.accion
                variant="add"
                href="{{ route('appointments.create', $selectedClient ? ['client' => $selectedClient->id] : []) }}">
          <x-iconos.nueva-cita clase="size-4 mr-2"/>
          Nueva cita
        </x-botones.accion>--}}
        <x-botones.icono-buton
          color="emerald"
          icon="nueva-cita"
          label="Nueva cita"
          texto="Nueva cita"
          onclick="window.location.href='{{ route('appointments.create', $selectedClient ? ['client' => $selectedClient->id] : []) }}'"
          />


      </div>
    </div>

    <div class="mt-4 flex flex-wrap items-center gap-4">
      @if ($selectedClient && ! $sentOnly)
        <flux:radio.group wire:model.live="dateFilter" variant="segmented" label="Citas"
                          class="border border-white/10 rounded-2xl gap-1"
                          :disabled="$showAllHistory">
          <flux:radio value="upcoming"
                      class="cursor-pointer bg-white/5 hover:bg-emerald-50/60 hover:text-white/60t transition-all duration-300 data-checked:bg-emerald-200/30! data-checked:text-emerald-200!">
            <x-iconos.proxima-cita/>
            Próximas
          </flux:radio>
          <flux:radio value="all"
                      class="cursor-pointer bg-white/5 hover:bg-emerald-50/60 hover:text-white/60t transition-all duration-300 data-checked:bg-emerald-200/30! data-checked:text-emerald-200!">
            <x-iconos.todos/>
            Todas
          </flux:radio>
          <flux:radio value="past"
                      class="cursor-pointer bg-white/5 hover:bg-emerald-50/60 hover:text-white/60t transition-all duration-300 data-checked:bg-emerald-200/30! data-checked:text-emerald-200!">
            <x-iconos.calendario-pasado/>
            Pasadas
          </flux:radio>
        </flux:radio.group>
      @endif

      @if ($showBulkActions && count($selectedAppointmentIds))
        <div class="mt-4 flex flex-wrap items-center gap-3">
          <flux:dropdown>
            <flux:button icon:trailing="chevron-down"
                         :disabled="$selectedAppointmentIds === []">
              Acciones masivas
            </flux:button>
            <flux:menu>
              <flux:menu.item variant="danger" icon="trash" wire:click="confirmBulkDelete">
                Eliminar seleccionadas
              </flux:menu.item>
            </flux:menu>
          </flux:dropdown>
          @if ($selectedAppointmentIds !== [])
            <flux:text>{{ count($selectedAppointmentIds) }} seleccionada(s)</flux:text>
          @endif
        </div>
      @endif
      @if($show_filters_nombre)
        <flux:field>
          <flux:label>Nombre</flux:label>
          <x-formularios.input wire:model.live.debounce.300ms="filter_nombre" placeholder="Filtrar por nombre"/>
        </flux:field>

        <flux:field>
          <flux:label>Apellidos</flux:label>
          <x-formularios.input wire:model.live.debounce.300ms="filter_apellidos"
                               placeholder="Filtrar por apellidos"/>
        </flux:field>
      @endif
      @unless ($sentOnly)
        <div class="flex flex-col items-center justify-center gap-2">
          <flux:label class="text-[14px] font-bold">Filtros</flux:label>
          <div class="flex items-center justify-center ml-6 gap-4">
            <flux:field class="flex flex-col">
              <flux:label>Enviadas</flux:label>
              <x-formularios.toggle
                      wire:model.live="filter_enviado" :disabled="$filter_activo || $filter_entregado"
                      :locked="$filter_activo || $filter_entregado"/>
            </flux:field>

            <flux:field class="flex flex-col">
              <flux:label>Entregadas</flux:label>
              <x-formularios.toggle
                      wire:model.live="filter_entregado" :disabled="$filter_enviado || $filter_activo"
                      :locked="$filter_enviado || $filter_activo"/>
            </flux:field>

            <flux:field class="flex flex-col">
              <flux:label>Supendidas</flux:label>
              <x-formularios.toggle
                      wire:model.live="filter_activo" :disabled="$filter_enviado || $filter_entregado"
                      :locked="$filter_enviado || $filter_entregado"/>
            </flux:field>
          </div>
        </div>
      @endunless
    </div>

    <div class="mt-4 overflow-hidden rounded-2xl border border-white/10">
      <table class="min-w-full divide-y divide-white/10 text-left text-sm">
        <thead class="bg-slate-900/70 text-slate-300">
        <tr>
          <x-tabla.th :condicion="$showBulkActions"/>
          <x-tabla.th-sort sortBy="cliente" :sortDirection="$sort_direction" :currentSort="$sort_by"/>
          <x-tabla.th-sort sortBy="fecha" :sortDirection="$sort_direction" :currentSort="$sort_by"/>
          <th class="px-4 py-3 text-center">Hora Cita</th>
          <x-tabla.th :condicion="$showSentColumns">
            <x-iconos.whatsapp clase="size-4"/>
            Enviado
          </x-tabla.th>
          <x-tabla.th :condicion="$showDeliveredColumns">
            <x-iconos.whatsapp clase="size-4"/>
            Entregado
          </x-tabla.th>
          <x-tabla.th :condicion="$showReadColumn">
            <x-iconos.whatsapp clase="size-4"/>
            Leído
          </x-tabla.th>
          <x-tabla.th :condicion="$showPendingColumn">
            Pendiente
          </x-tabla.th>
          <th class="px-4 pr-16 text-right">Acciones</th>
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
                : ($sentOnly ? $editUrl : route('appointments.index', ['client' => $appointment->client_id]));
          @endphp
          <tr wire:key="appointment-{{ $appointment->id }}"
              role="link" tabindex="0"
              onclick="window.location='{{ $rowUrl }}'"
              onkeydown="if (event.key === 'Enter' || event.key === ' ') { event.preventDefault(); window.location='{{ $rowUrl }}'; }"
              class="cursor-pointer transition-colors hover:bg-white/5 {{ $canChange ? '' : 'bg-slate-900/50 text-slate-400' }}"

              @if ($appointment->enviado && $appointment->latestWhatsAppMessage?->provider_message_id)
                title="Message SID: {{ $appointment->latestWhatsAppMessage?->provider_message_id }}"
                  @endif >
            @if ($showBulkActions)
              <td class="px-4 py-3" onclick="event.stopPropagation()">
                <flux:checkbox
                        wire:model.live="selectedAppointmentIds"
                        value="{{ $appointment->id }}"
                        aria-label="Seleccionar cita de {{ $appointment->client?->full_name }}"
                />
              </td>
            @endif
            <td class="px-4 py-3">
              <a href="{{ $rowUrl }}"
                 class="inline-flex items-center gap-2 font-medium {{ $canChange ? 'text-emerald-300 hover:text-emerald-200' : 'text-slate-400 hover:text-slate-300' }}">
                <span>{{ $appointment->client?->nombre }} {{ $appointment->client?->apellidos }}</span>
                @if (! $sentOnly && ($appointment->appointments_count ?? 1) > 1)
                  <span class="inline-flex items-center rounded-full bg-sky-500/15 px-2 py-0.5 text-xs font-semibold text-sky-200 ring-1 ring-inset ring-sky-400/30"
                        title="{{ $appointment->appointments_count }} citas"
                        aria-label="{{ $appointment->appointments_count }} citas">
                    {{ $appointment->appointments_count }}
                  </span>
                @endif
              </a>
              <p class="text-xs text-slate-400">{{ $appointment->client?->telefono }}</p>
              {{$appointment->id}}
            </td>
            <td class="px-4 py-3 text-center">{{ucwords($appointment->fecha?->translatedFormat('l, d - F - Y'))}}</td>
            <td class="px-4 py-3 text-center text-xs">{{ $appointment->hora }}</td>
            @if ($showSentColumns)
              <td class="px-4 py-3 text-center">
                <div class="relative flex flex-col items-center justify-center gap-1">
                  @if(!$appointment->latestWhatsAppMessage?->provider_message_id)
                    @if(!$appointment->enviado )
                      @if($canSendNow)
                        <div class="flex flex-col items-center jusitfy-center text-amber-200/50 text-xs ">
                          <x-iconos.whatsapp/>
                          En cola
                        </div>
                      @else
                        <div class="flex flex-col items-center jusitfy-center text-red-500/70 text-xs ">
                          <x-iconos.whatsapp/>
                          Desactivado
                        </div>
                      @endif
                @else
                    <x-iconos.alert clase="text-red-400/50 size-6"/>
                    <h6 class="text-[10px] text-red-500/60">Error de envío o se ha pasado de fecha</h6>
                @endif
                @else
                  <span class="flex items-center justify-center {{ $appointment->enviado && $appointment->whatsapp_sent_at ? 'text-green-400' : 'text-slate-300/40' }}">
                    <span class="size-1 absolute top-1 left-1/2 rounded-full bg-red-500 {{ $appointment->whatsapp_sent_at ? 'hidden' : 'visible' }}"></span>
                    <x-iconos.doble-check/>
                  </span>
                  <h6 class="text-[10px] text-slate-400">
                    {{ $appointment->whatsapp_sent_at?->format('H:i d/m/Y') }}
                  </h6>
                  @endif
                </div>
                </td>
                @endif
            @if ($showDeliveredColumns)
              <td class="px-4 py-3 text-center">
                @if($appointment->esFallido)
                  <div class="relative flex flex-col items-center justify-center gap-1">
                    <x-iconos.alert clase="text-red-400/50 size-6"/>
                    <h6 class="text-[10px] text-red-500/60">No entregado</h6>
                  </div>
                @elseif($appointment->latestWhatsAppMessage?->provider_message_id)
                  <div class="relative flex flex-col items-center justify-center gap-1">
                    <span class="flex items-center justify-center
                    {{ $appointment->entregado && $appointment->whatsapp_delivered_at ? 'text-green-400' : 'text-slate-300/40' }}">
                      <span class="size-1 absolute top-1 left-1/2 rounded-full bg-red-500
                      {{ $appointment->whatsapp_delivered_at  ? 'hidden' : 'visible' }}">
                      </span>
                      <x-iconos.doble-check/>
                    </span>
                    <h6 class="text-[10px] text-slate-400">
                      {{ $appointment->whatsapp_delivered_at?->format('H:i d/m/Y') }}
                    </h6>
                  </div>
                @endif
              </td>
            @endif
            @if ($showReadColumn)
              <td class="px-4 py-3">
                @if($appointment->latestWhatsAppMessage?->provider_message_id)
                  <div class="relative flex flex-col items-center justify-center gap-1">
                    @if($appointment->entregado)
                      <x-iconos.doble-check
                              clase="size-6 {{ filled($appointment->whatsapp_read_at) ? 'text-green-400' : 'text-gray-400' }}"/>
                      <h6 class="text-[10px] text-slate-400">
                        {{ $appointment->whatsapp_read_at?->format('H:i d/m/Y') }}
                      </h6>
                    @else
                      <x-iconos.alert clase="text-red-600/50 size-6"/>
                    @endif
                  </div>
                @endif
              </td>
            @endif
            @if ($showPendingColumn)
      <td class="px-4 py-3 text-center" onclick="event.stopPropagation()">
        @if ($canChange && ! $appointment->enviado)
          <x-formularios.toggle
                  :estado="$appointment->activo ? 'Sí' : 'No'"
                  :checked="$appointment->activo"
                  wire:change="updateActiveStatus({{ $appointment->id }}, $event.target.checked)"/>
        @endif
      </td>
    @endif
            <td class="px-4 py-3 text-right" onclick="event.stopPropagation()">
      <div class="flex justify-end items-center gap-2">
        @if ($canSendNow)
          <x-botones.icono-buton
                  color="emerald"
                  icon="whatsapp"
                  label="Enviar WhatsApp"
                  x-on:click="localStorage.setItem('autoSyncAfterSend', '1')"
                  wire:click="sendNow({{ $appointment->id }})"
                  wire:loading.attr="disabled"
                  wire:target="sendNow({{ $appointment->id }})"
          />
        @endif
        @if ($canChange)
            <x-botones.icono-buton
                    color="blue"
                    icon="lapiz"
                    label="Editar cita"
                    onclick="window.location='{{ $editUrl }}'"
            />
        @endif
          <x-botones.icono-buton
                  color="red"
                  icon="papelera"
                  label="Eliminar cita"
                  wire:click="confirmDelete({{ $appointment->id }})"
          />
            </div>
          </td>
       </tr>
    @empty
      <tr>
        <td class="px-4 py-6 text-slate-400"
            colspan="{{ 4 + ($showBulkActions ? 1 : 0) + ($showSentColumns ? 1 : 0) + ($showDeliveredColumns ? 1 : 0) + ($showReadColumn ? 1 : 0) + ($showPendingColumn ? 1 : 0) }}">
          {{ $sentOnly ? 'No hay citas enviadas para mostrar todavía.' : 'No hay citas para mostrar todavía.' }}
        </td>
      </tr>
      @endforelse
      </tbody>
      </table>
  </div>

  <div class="mt-4">
    {{ $appointments->links('vendor.pagination.tailwind') }}
  </div>
</div>

@if ($appointmentPendingDeletion)
  <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/80 px-4 py-6">
    <div class="w-full max-w-md rounded-3xl border border-white/10 bg-slate-900 p-6 shadow-2xl">
      <h3 class="text-lg font-semibold">Eliminar cita</h3>
      <p class="mt-3 text-sm text-slate-300">
        ¿Seguro que quieres eliminar la cita de
        <span class="font-medium text-white">{{ $appointmentPendingDeletion->client?->full_name }}</span>
        del {{ $appointmentPendingDeletion->fecha?->format('d/m/Y') }} a
        las {{ $appointmentPendingDeletion->hora }}?
      </p>
      <p class="mt-2 text-sm text-slate-400">Esta acción no se puede deshacer.</p>

      <div class="mt-6 flex flex-wrap justify-end gap-2">
        <x-botones.icono-buton
          color="amber"
          label="Cancelar"
          texto="Cancelar"
          type="submit"
          icon="volver"
          wire:click="cancelDelete" />

        <x-botones.icono-buton
           color="red"
           icon="papelera"
           label="Eliminar cita"
           texto="Eliminar cita"
           wire:click="deleteConfirmed" />
      </div>
    </div>
  </div>
@endif

<flux:modal wire:model.self="bulkDeleteConfirmationOpen" class="min-w-88">
  <div class="space-y-6">
    <div>
      <flux:heading size="lg">¿Eliminar citas seleccionadas?</flux:heading>
      <flux:text class="mt-2">
        Se eliminarán {{ count($selectedAppointmentIds) }} cita(s). Esta acción no se puede deshacer.
      </flux:text>
    </div>
    <div class="flex gap-2">
      <flux:spacer/>
      <flux:modal.close>
        <flux:button variant="ghost">Cancelar</flux:button>
      </flux:modal.close>
      <flux:button variant="danger" wire:click="deleteSelected">Eliminar</flux:button>
    </div>
  </div>
</flux:modal>

@script
<script>
  if (localStorage.getItem('autoSyncAfterSend') === '1') {
    localStorage.removeItem('autoSyncAfterSend');
    setTimeout(() => $wire.syncDeliveryStatuses(), 3000);
  }
</script>
@endscript
