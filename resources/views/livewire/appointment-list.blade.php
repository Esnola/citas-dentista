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
            <span
                    class="text-xs font-medium text-slate-400">Sincronizado: {{ $deliveryStatusesSyncedAt }}</span>
          @endif
        </div>
      </div>
      <div class="flex flex-wrap items-center gap-6">
        <x-botones.accion
                variant="indigo"
                type="button"
                wire:click="syncDeliveryStatuses"
                wire:loading.attr="disabled"
                wire:target="syncDeliveryStatuses"
        >
          <x-iconos.reload clase="size-4 mr-2" wire:loading.class="animate-spin"/>
          Actualizar Datos
        </x-botones.accion>
        @if ($selectedClient && ! $sentOnly)
          <x-botones.accion href="{{ route('appointments.index') }}" icono="calendar"
          >Todas las citas
          </x-botones.accion>
        @endif
        @if (! $sentOnly)
          <x-botones.accion href="{{ route('appointments.sent') }}" icono="whatsapp">
            Citas enviadas
          </x-botones.accion>
        @else
          <x-botones.accion href="{{ route('appointments.index') }}" icono="calendar">
            Todas las citas
          </x-botones.accion>
        @endif
        <x-botones.accion
                variant="add"
                href="{{ route('appointments.create', $selectedClient ? ['client' => $selectedClient->id] : []) }}">
          <x-iconos.nueva-cita clase="size-4 mr-2"/>
          Nueva cita
        </x-botones.accion>
      </div>
    </div>

    <div class="mt-4 flex flex-wrap items-center gap-4">
      @if ($selectedClient && ! $sentOnly)
        <flux:radio.group wire:model.live="dateFilter" variant="segmented" label="Citas">
          <flux:radio value="upcoming">Próximas</flux:radio>
          <flux:radio value="all">Todas</flux:radio>
          <flux:radio value="past">Pasadas</flux:radio>
        </flux:radio.group>
      @endif

      <flux:field>
        <flux:label>Nombre</flux:label>
        <x-formularios.input wire:model.live.debounce.300ms="filter_nombre" placeholder="Filtrar por nombre"/>
      </flux:field>

      <flux:field>
        <flux:label>Apellidos</flux:label>
        <x-formularios.input wire:model.live.debounce.300ms="filter_apellidos"
                             placeholder="Filtrar por apellidos"/>
      </flux:field>

      @unless ($sentOnly)
        <div class="flex flex-col items-center justify-center gap-2">
          <flux:label class="text-[14px] font-bold">Notificaciones</flux:label>
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
          <th class="px-4 py-3">
            <button type="button"
                    class="inline-flex cursor-pointer items-center gap-1 font-semibold text-slate-200 hover:text-white"
                    wire:click="sortByColumn('cliente')" title="Ordenar por cliente"
                    aria-label="Ordenar por cliente">
              Cliente
              <span class="text-xs text-slate-400">
                @if ($sort_by === 'cliente')
                  @if( $sort_direction === 'asc' )
                    <x-iconos.deAZ/>
                  @else
                    <x-iconos.deZA/>
                  @endif
                @else
                  <x-iconos.deAZ/>
                @endif
              </span>
            </button>
          </th>
          <th class="px-4 py-3">
            <button type="button"
                    class="flex cursor-pointer items-center justify-center w-full gap-1 font-semibold text-slate-200 hover:text-white"
                    wire:click="sortByColumn('fecha')"
                    title="Ordenar por fecha"
                    aria-label="Ordenar por fecha">
              Fecha Cita
              <span class="text-xs text-slate-400">
                @if ($sort_by === 'fecha')
                  @if( $sort_direction === 'asc' )
                    <x-iconos.num-Asc/>
                  @else
                    <x-iconos.num-Desc/>
                  @endif
                @else
                  <x-iconos.num-Asc/>
                @endif
              </span>
            </button>
          </th>
          <th class="px-4 py-3 text-center">Hora Cita</th>
          @if ($showSentColumns)
            <th class="px-4 py-3 text-xs">
              <div class="flex items-center justify-center gap-2">
                <x-iconos.whatsapp clase="size-4"/>
                Enviado
              </div>
            </th>
          @endif
          @if ($showDeliveredColumns)
            <th class="px-4 py-3 text-xs">
              <div class="flex items-center justify-center gap-2">
                <x-iconos.whatsapp clase="size-4"/>
                Entregado
              </div>
            </th>
          @endif
          @if ($showReadColumn)
            <th class="px-4 py-3 text-xs">
              <div class="flex items-center justify-center gap-2">
                <x-iconos.whatsapp clase="size-4"/>
                Leído
              </div>
            </th>
          @endif
          @if ($showPendingColumn)
            <th class="px-4 py-3">Pendiente</th>
          @endif
          <th class="px-4 py-3 text-center">Acciones</th>
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
                  @endif
          >
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
            </td>
            <td class="px-4 py-3 text-center">{{ucwords($appointment->fecha?->translatedFormat('l, d - F - Y'))}}</td>
            <td class="px-4 py-3 text-center text-xs">{{ $appointment->hora }}</td>
            <td class="px-4 py-3 text-center">
              @if ($showSentColumns)
                <div class="relative flex flex-col items-center justify-center gap-1">
                  @if(!$appointment->latestWhatsAppMessage?->provider_message_id)
                    @if(!$appointment->enviado)
                      <div class="flex items-center jusitfy-center p-1 bg-amber-400/30 border border-amber-300 rounded-full">
                        <x-iconos.reloj-agujas clase="size-4 text-amber-300"/>
                      </div>
                      <h6 class="text-[10px] text-amber-400">En cola</h6>
                    @else
                      <x-iconos.alert clase="text-red-400/50 size-6"/>
                      <h6 class="text-[10px] text-red-500/60">Error de envío o se ha pasado de fecha</h6>
                    @endif
                  @else
                    <span class="flex items-center justify-center
                  {{ $appointment->enviado && $appointment->whatsapp_sent_at ? 'text-green-400' : 'text-slate-300/40' }}">
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
                @if($appointment->latestWhatsAppMessage?->provider_message_id)
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
              <td class="px-4 py-3 ">
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
                @endif
              </td>
            @endif
            @if ($showPendingColumn)
              <td class="px-4 py-3" onclick="event.stopPropagation()">
                @if ($canChange && ! $appointment->enviado)
                  <x-formularios.toggle
                          :estado="$appointment->activo ? 'Sí' : 'No'"
                          :checked="$appointment->activo"
                          wire:change="updateActiveStatus({{ $appointment->id }}, $event.target.checked)"/>
                @endif
              </td>
            @endif
            <td class="px-4 py-3 text-right" onclick="event.stopPropagation()">
              <div class="flex justify-center items-center gap-2">
                @if ($canSendNow)
                  <x-botones.accion
                          variant="add"
                          size="sm"
                          type="button"
                          wire:click="sendNow({{ $appointment->id }})"
                          wire:loading.attr="disabled"
                          wire:target="sendNow({{ $appointment->id }})">
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
                          title="Editar cita"/>
                @endif
                <x-botones.accion
                        variant="delete"
                        size="icon"
                        icono="delete"
                        type="button"
                        wire:click="confirmDelete({{ $appointment->id }})"
                        aria-label="Eliminar cita"
                        title="Eliminar cita"/>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td class="px-4 py-6 text-slate-400"
                colspan="{{ 4 + ($showSentColumns ? 2 : 0) + ($showDeliveredColumns ? 2 : 0) + ($showReadColumn ? 1 : 0) + ($showPendingColumn ? 1 : 0) }}">
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
          <x-botones.accion type="button" wire:click="cancelDelete">Cancelar</x-botones.accion>
          <x-botones.accion variant="delete" icono="delete" type="button" wire:click="deleteConfirmed">
            Eliminar
          </x-botones.accion>
        </div>
      </div>
    </div>
  @endif
</div>
