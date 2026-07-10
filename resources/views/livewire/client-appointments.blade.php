<div class="grid gap-6"
     wire:poll.10s="autoSync"
     x-on:reload-appointment-list.window="
       setTimeout(() => $wire.syncDeliveryStatuses(), 3000)
     ">
  <div class='rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur '>
    <div class="flex flex-wrap items-center justify-between gap-4">
      <div class="flex gap-6 items-center">
        <x-iconos.calendar/>
        <h2 class="text-xl font-semibold">
          {{ $selectedClient->full_name }}
        </h2>
        <h3 class="rounded-2xl border border-green-300/70 shadow-xs shadow-green-300 bg-slate-900/60 px-4 py-3 text-sm text-slate-300">
          {{ $appointmentsCount }} cita{{ $appointmentsCount > 1 ? 's' : '' }}
        </h3>
        @if ($deliveryStatusesSyncedAt)
          <span class="text-xs font-medium text-slate-400">Sincronizado: {{ $deliveryStatusesSyncedAt }}</span>
        @endif
      </div>
      <div class="flex flex-wrap items-center gap-2">

        <x-botones.icono-buton
                color="emerald"
                icon="nueva-cita"
                label="Nueva cita"
                texto="Nueva cita"
                onclick="window.location.href='{{ route('appointments.create', ['client' => $selectedClient->id]) }}'"
        />
        <x-botones.icono-buton
                color="indigo"
                icon="reload"
                label="Sincronizar Datos"
                texto="Sincronizar Datos"
                wire:click="syncDeliveryStatuses"
                wire:loading.attr="disabled"
                wire:target="syncDeliveryStatuses"
        />

      </div>
    </div>
    {{--    DIV FILTROS --}}
    <div class="mt-12 grid grid-cols-[220px_1fr_auto] items-center justify-between w-full">
      {{--    INICIO BULK ACTIONS--}}
      <div class="flex items-center">
        @if ($showBulkActions && count($selectedAppointmentIds))
          <div x-data="{ open: false }" @click.away="open = false" class="relative">
            <button @click="open = !open"
                    class="flex items-center gap-2 rounded-2xl border border-emerald-400/30 bg-emerald-700/10 px-4 py-3 text-sm text-emerald-300/70 hover:bg-emerald-500/20 hover:text-emerald-200 transition-all duration-300 cursor-pointer">
              <x-iconos.whatsapp clase="size-4"/>
              {{ count($selectedAppointmentIds) }} {{ count($selectedAppointmentIds) === 1 ? 'cita seleccionada' : 'citas seleccionadas' }}
              <x-iconos.down clase="size-3"/>
            </button>
            <div x-show="open" x-transition
                 class="absolute left-0 z-50 mt-2 min-w-60 rounded-2xl border border-emerald-400/30 bg-emerald-900 p-2 shadow-2xl shadow-emerald-500/10 backdrop-blur">
              <button wire:click="updateSelectedActiveStatus(true)"
                      class="flex w-full items-center gap-2 px-3 py-2 text-left text-sm text-emerald-200 transition-colors hover:bg-emerald-500/20 hover:text-emerald-100 cursor-pointer">
                <x-iconos.check clase="size-4"/>
                Activar Whatsapp
              </button>
              <button wire:click="updateSelectedActiveStatus(false)"
                      class="flex w-full items-center gap-2 rounded-xl px-3 py-2 text-left text-sm text-amber-200 transition-colors hover:bg-amber-500/20 hover:text-amber-100 cursor-pointer">
                <x-iconos.inactivo clase="size-4"/>
                Desactivar Whatsapp
              </button>
              <div class="my-2 border-t border-emerald-400/20"></div>
              <button wire:click="updateSelectedCitaActiva(true)"
                      class="flex w-full items-center gap-2 rounded-xl px-3 py-2 text-left text-sm text-emerald-200 transition-colors hover:bg-emerald-500/20 hover:text-emerald-100 cursor-pointer">
                <x-iconos.usuario-plus clase="size-4"/>
                Activar cita
              </button>
              <button wire:click="updateSelectedCitaActiva(false)"
                      class="flex w-full items-center gap-2 rounded-xl px-3 py-2 text-left text-sm text-amber-200 transition-colors hover:bg-amber-500/20 hover:text-amber-100 cursor-pointer">
                <x-iconos.user-menos clase="size-4"/>
                Desactivar cita
              </button>
              <div class="my-2 border-t border-emerald-400/20"></div>
              <button wire:click="confirmBulkDelete"
                      class="flex w-full items-center gap-2 px-3 py-2 text-left text-sm text-red-300 transition-colors hover:bg-red-500/20 hover:text-red-200 cursor-pointer">
                <x-iconos.papelera clase="size-4"/>
                Eliminar seleccionadas
              </button>
            </div>
          </div>
        @endif
      </div>
      {{--FIN BULK ACTIONS--}}
      <div class="mx-auto">
        <h4 class="text-center text-lg font-bold mb-2">Citas</h4>
        <flux:radio.group variant="segmented"
                          wire:model.live="filter"
                          class="border border-emerald-400/30 rounded-2xl gap-1 span-2"
        >
          <flux:radio value="upcoming"
                      class="cursor-pointer bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-300/70 transition-all duration-300 data-checked:bg-emerald-500/25! data-checked:text-emerald-200! data-checked:shadow-sm data-checked:shadow-emerald-500/10!">
            <x-iconos.proxima-cita/>
            Próximas
          </flux:radio>
          <flux:radio value="past"
                      class="cursor-pointer bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-300/70 transition-all duration-300 data-checked:bg-emerald-500/25! data-checked:text-emerald-200! data-checked:shadow-sm data-checked:shadow-emerald-500/10!">
            <x-iconos.calendario-pasado/>
            Pasadas
          </flux:radio>
          <flux:radio value="all"
                      class="cursor-pointer bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-300/70 transition-all duration-300 data-checked:bg-emerald-500/25! data-checked:text-emerald-200! data-checked:shadow-sm data-checked:shadow-emerald-500/10!">
            <x-iconos.todos/>
            Todas
          </flux:radio>

        </flux:radio.group>
      </div>
      <div class="flex flex-col items-center justify-center gap-2">
        <flux:label class="text-[12px] font-bold">Whatsapps</flux:label>
        <div class="flex items-center justify-center gap-4">
          @foreach(['sent' => 'Enviados', 'delivered' => 'Entregados', 'unsent' => 'Sin Envío'] as $value => $label)
            <div wire:click="toggleWhatsappFilter('{{ $value }}')"
                 class="inline-flex items-center gap-2 rounded-2xl border px-4 py-3 transition-colors cursor-pointer
                        {{ $whatsappFilter === $value
                           ? 'border-emerald-400/30 bg-emerald-400/10'
                           : 'border-white/10 bg-slate-950/40 hover:border-emerald-400/20 hover:bg-emerald-400/10' }}">
              <span class="h-5 w-9 rounded-full transition after:block after:h-4 after:w-4 after:translate-x-0.5 after:translate-y-0.5 after:rounded-full after:bg-white after:transition
                           {{ $whatsappFilter === $value ? 'bg-emerald-400 after:translate-x-4' : 'bg-slate-700' }}"></span>
              <span class="text-xs text-slate-200">{{ $label }}</span>
            </div>
          @endforeach
        </div>
      </div>
    </div>
    {{--    INICION TABLA DATOS--}}
    <div class="mt-4 overflow-hidden rounded-2xl border border-white/10">
      <table class="min-w-full divide-y divide-white/10 text-left text-sm">
        <thead class="bg-slate-900/70 text-slate-300">
        <tr>
          <th class="px-4 py-3 text-xs">
            <input type="checkbox"
                   wire:key="select-all-appointments"
                   class="size-4 cursor-pointer rounded border-white/20 bg-slate-950/50 text-emerald-500 accent-emerald-500 focus:ring-2 focus:ring-emerald-400/40 focus:ring-offset-0"
                   wire:model.live="selectAll"
                   wire:change="toggleVisibleAppointments(@js($visibleAppointmentIds))"
                   aria-label="{{ $selectAll ? 'Deseleccionar todas las citas visibles' : 'Seleccionar todas las citas visibles' }}"
            >
          </th>
          <th class="px-4 py-3">Cliente</th>
          <x-tabla.th-sort sortBy="fecha" :sortDirection="$sort_direction" currentSort="fecha"/>
          <th class="px-4 py-3 text-center">Hora Cita</th>
          <th class="px-4 py-3 text-center">
            <div class="flex items-center justify-center gap-2">
              <x-iconos.whatsapp clase="size-4"/>
              Enviado
            </div>
          </th>
          <th class="px-4 py-3 text-center">
            <div class="flex items-center justify-center gap-2">
              <x-iconos.whatsapp clase="size-4"/>
              Entregado
            </div>
          </th>
          <th class="px-4 py-3 text-center">
            <div class="flex items-center justify-center gap-2">
              <x-iconos.whatsapp clase="size-4"/>
              Leído
            </div>
          </th>
          <th class="px-4 py-3 text-center">
            Confir / Repro
          </th>
          <th class="px-4 py-3 text-xs text-center">Envío</th>
          <th class="px-4 py-3 text-xs text-center">Cita activa</th>
          <th class="px-4 py-3 text-xs text-right">Acciones</th>
        </tr>
        </thead>
        <tbody class="divide-y divide-white/10 bg-slate-950/40">
        @forelse ($appointments as $appointment)
          @php
            $editUrl = route('appointments.edit', $appointment);
            $esPasado = ! $appointment->isFuture();
            $esActiva = $appointment->cita_activa;
            $rowClasses = ['cursor-pointer transition-colors hover:bg-white/5 focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-emerald-400/60 focus-visible:bg-white/5',
           'bg-slate-400/15' => $esPasado,
           'bg-red-500/5' => ! $esActiva
           ];
          @endphp
          <tr wire:key="appointment-{{ $appointment->id }}"
              role="link" tabindex="0"
              onclick="window.location='{{ $editUrl }}'"
              onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();window.location='{{ $editUrl }}';}else if(event.key==='ArrowDown'){event.preventDefault();this.nextElementSibling?.focus();}else if(event.key==='ArrowUp'){event.preventDefault();this.previousElementSibling?.focus();}"
              class="cursor-pointer transition-colors hover:bg-white/5 focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-emerald-400/60 focus-visible:bg-white/5 {{ $esPasado ? 'bg-slate-400/15' : '' }}
              {{ ! $esActiva ? 'bg-red-500/5' : '' }}"


              @if ($appointment->enviado && $appointment->latestWhatsAppMessage?->provider_message_id)
                title="Message SID: {{ $appointment->latestWhatsAppMessage?->provider_message_id }}"
                  @endif >
            <td class="px-4 py-3 text-center" onclick="event.stopPropagation()">
              <input type="checkbox"
                     class="size-4 cursor-pointer rounded border-white/20 bg-slate-950/50 text-emerald-500 accent-emerald-500 focus:ring-2 focus:ring-emerald-400/40 focus:ring-offset-0"
                     wire:model.live="selectedAppointmentIds"
                     value="{{ $appointment->id }}"
                     aria-label="Seleccionar cita de {{ $appointment->client?->full_name }}">
            </td>
            <td class="px-4 py-3">
              <a href="{{ $editUrl }}" tabindex="-1"
                 class="inline-flex items-center gap-2 font-medium {{ ($esPasado || ! $esActiva) ? 'text-slate-400 hover:text-slate-300' : 'text-emerald-300 hover:text-emerald-200' }}">
                <span>{{ $appointment->client?->nombre }} {{ $appointment->client?->apellidos }}</span>
              </a>
            </td>
            <td class="px-4 py-3 text-center text-xs">{{ucwords($appointment->fecha?->translatedFormat('l, d - F - Y'))}}</td>
            <td class="px-4 py-3 text-center text-xs">{{ Str::substr($appointment->hora, 0, 5) }}</td>
            <td class="px-4 py-3 text-center">
              <div class="relative flex flex-col items-center justify-center gap-1">
                @if($appointment->enviado)
                  <span class="flex items-center justify-center text-green-400">
                    <x-iconos.doble-check/>
                  </span>
                  <h6 class="text-[10px] text-slate-400">
                    {{ $appointment->whatsapp_sent_at?->format('H:i d/m/Y') }}
                  </h6>
                @elseif($appointment->latestWhatsAppMessage?->provider_message_id)
                  <span class="flex items-center justify-center text-slate-300/40">
                    <x-iconos.doble-check/>
                  </span>
                @else
                  <div class="flex flex-col items-center justify-center text-slate-500 text-xs">
                    <x-iconos.whatsapp clase="size-5"/>
                    @if($appointment->isFuture())
                      Pendiente
                    @else
                      No enviado
                    @endif
                  </div>
                @endif
              </div>
            </td>
            <td class="px-4 py-3 text-center">
              @if($appointment->enviado)
                <div class="relative flex flex-col items-center justify-center gap-1">
                  @if($appointment->entregado)
                    <span class="flex items-center justify-center text-green-400">
                      <x-iconos.doble-check/>
                    </span>
                    <h6 class="text-[10px] text-slate-400">
                      {{ $appointment->whatsapp_delivered_at?->format('H:i d/m/Y') }}
                    </h6>
                  @else
                    <span class="flex items-center justify-center text-slate-500">
                      <x-iconos.doble-check clase="size-6"/>
                    </span>
                  @endif
                </div>
              @endif
            </td>
            <td class="px-4 py-3 text-center">
              @if($appointment->entregado)
                <div class="relative flex flex-col items-center justify-center gap-1">
                  @if($appointment->whatsapp_read_at)
                    <span class="flex items-center justify-center text-green-400">
                      <x-iconos.doble-check/>
                    </span>
                    <h6 class="text-[10px] text-slate-400">
                      {{ $appointment->whatsapp_read_at?->format('H:i d/m/Y') }}
                    </h6>
                  @else
                    <span class="flex items-center justify-center text-slate-500">
                      <x-iconos.doble-check clase="size-6"/>
                    </span>
                  @endif
                </div>
              @endif
            </td>
            <td class="px-4 py-3 text-center text-xs">
              @php
                $esConfirmada = $appointment->esCitaConfirmada();
                $retorno = $appointment->queBoton();
                $tieneMensaje = $appointment->hasTextResponse() ;


                if($retorno === 'confirmada'){
                    $icono =  'usuario-plus';
                    $displayResponseLabel = 'Confirmada';
                    $responseClasses = 'bg-emerald-500/15 text-emerald-300 border border-emerald-400/30';
                    }
                elseif($retorno === 'cambiar'){
                  $icono = 'alert';
                  $displayResponseLabel = 'Reprogramar';
                  $responseClasses = 'bg-red-500/15 text-red-300 border border-red-400/30';
                }elseif($tieneMensaje){
                    $icono =  'ojo';
                    $displayResponseLabel = 'Leer Mensaje';
                    $responseClasses = 'bg-amber-500/15 text-amber-300 border border-amber-400/30';
                }else{
                  $icono = '';
                  $displayResponseLabel =  '';
                  $responseClasses = '';
                }

                $respondedAt = $appointment->latestInboundAfterLastSent()?->responded_at;
              @endphp

              @if ($displayResponseLabel || $appointment->wasRescheduled())
                <div class="flex flex-col items-center gap-1">
                    <span class="inline-flex items-center rounded-full gap-2 px-2 py-0.5 text-xs font-semibold border {{ $responseClasses }}">
                       <x-dynamic-component :component="'iconos.' . $icono" class="size-6 "/>
                     {{$displayResponseLabel}}
                      </span>

                  @if ($appointment->wasRescheduled())
                    <span class="flex items-center gap-2 rounded-full bg-amber-500/15 px-2 py-0.5 text-[10px] font-semibold text-amber-300 ring-1 ring-inset ring-amber-400/30">
                      <x-iconos.ajustes clase="size-5"/>
                      Reprogramada
                    </span>
                  @endif
                  @if ($respondedAt)
                    <span class="text-[10px] text-slate-400">{{ $respondedAt->format('H:i d/m/Y') }}</span>
                  @endif
                </div>
              @else
                <span class="text-slate-500">—</span>
              @endif
            </td>
            <td class="px-4 py-3 text-center" onclick="event.stopPropagation()">
              @if ($appointment->enviado && $appointment->scheduledFor()->isFuture())
                <x-botones.icono-buton
                        color="emerald"
                        icon="whatsapp"
                        label="Reenviar"
                        texto="Reenviar"
                        especialtexto="text-xs!"
                        wire:click="confirmResend({{ $appointment->id }})"
                        wire:loading.attr="disabled"
                        wire:target="confirmResend({{ $appointment->id }})"
                />
              @else
                @if($appointment->scheduledFor()->isFuture())
                  <x-formularios.toggle
                          :checked="$appointment->activo"
                          wire:change="updateActiveStatus({{ $appointment->id }}, $event.target.checked)"/>
                @else
                  <span class="inline-flex items-center gap-2 rounded-2xl border px-4 py-3 border-white/5 bg-slate-950/25 opacity-80">
                    <span class="h-5 w-9 rounded-full bg-slate-700 after:block after:h-4 after:w-4 after:translate-x-0.5 after:translate-y-0.5 after:rounded-full after:bg-slate-400"></span>
                  </span>
                @endif
              @endif
            </td>
            <td class="px-4 py-3 text-center" onclick="event.stopPropagation()">
              @if($appointment->isFuture())
                <x-formularios.toggle
                        :checked="$esActiva"
                        offColor="bg-red-500"
                        wire:change="updateAppointmentActiveStatus({{ $appointment->id }}, $event.target.checked)"/>
              @else
                <span class="inline-flex items-center gap-2 rounded-2xl border px-4 py-3 border-white/5 bg-slate-950/25 opacity-80">
                  <span class="h-5 w-9 rounded-full bg-slate-700 after:block after:h-4 after:w-4 after:translate-x-0.5 after:translate-y-0.5 after:rounded-full after:bg-slate-400"></span>
                </span>
              @endif
            </td>
            <x-tabla.botones-maniobra :appointment="$appointment" :editUrl="$editUrl"/>
          </tr>
        @empty
          <tr>
            <td class="px-4 py-6 text-slate-400" colspan="11">
              No hay citas para mostrar todavía.
            </td>
          </tr>
        @endforelse
        </tbody>
      </table>
    </div>
    {{--    FIN TABLA DATOS--}}
    <div class="mt-4">
      {{ $appointments->links('vendor.pagination.tailwind') }}
    </div>
  </div>

  {{-- MODAL DE CONFIRMACION BORRADO --}}

  @if ($appointmentPendingDeletion)
    <x-modales.confirmacion x-data="{ modalOpen: true }" x-trap.noscroll="modalOpen"
                            x-on:keydown.escape.window="$wire.cancelDelete()" titulo="Eliminar cita">
      <p class="mt-3 text-sm text-slate-300">
        ¿Seguro que quieres eliminar la cita de
        <span class="font-medium text-white">{{ $appointmentPendingDeletion->client?->full_name }}</span>
        del {{ $appointmentPendingDeletion->fecha?->format('d/m/Y') }} a
        las {{ Str::substr($appointmentPendingDeletion->hora, 0, 5) }}?
      </p>
      <p class="mt-2 text-sm text-slate-400">Esta acción no se puede deshacer.</p>

      <x-slot:actions>
        <x-botones.icono-buton color="amber" label="Cancelar" texto="Cancelar" icon="volver"
                               wire:click="cancelDelete"/>
        <x-botones.icono-buton color="red" icon="papelera" label="Eliminar cita" texto="Eliminar cita"
                               wire:click="deleteConfirmed"/>
      </x-slot:actions>
    </x-modales.confirmacion>
  @endif
  {{-- MODAL DE CONFIRMACION REENVIO --}}
  <x-modales.confirmacion-reenvio wire:target="confirmResend" :appointmentPendingResend="$appointmentPendingResend"/>
  {{--
    @if ($appointmentPendingResend)
      <x-modales.confirmacion x-data="{ modalOpen: true }" x-trap.noscroll="modalOpen"
                              x-on:keydown.escape.window="$wire.cancelResend()" titulo="Reenviar WhatsApp">
        <p class="mt-3 text-sm text-slate-300">Ya se ha enviado un WhatsApp de esta cita.</p>
        <p class="mt-2 text-sm text-slate-400">¿Quieres enviarlo de nuevo?</p>

        <x-slot:actions>
          <x-botones.icono-buton color="amber" label="Cancelar" texto="Cancelar" icon="volver"
                                 wire:click="cancelResend"/>
          <x-botones.icono-buton color="emerald" icon="whatsapp" label="Reenviar" texto="Reenviar"
                                 wire:click="resendConfirmed"/>
        </x-slot:actions>
      </x-modales.confirmacion>
    @endif
  --}}

  @if ($bulkDeleteConfirmationOpen)
    <x-modales.confirmacion x-data="{ modalOpen: true }" x-trap.noscroll="modalOpen"
                            x-on:keydown.escape.window="$wire.$set('bulkDeleteConfirmationOpen', false)"
                            titulo="Eliminar citas seleccionadas">
      <p class="mt-3 text-sm text-slate-300">
        Se eliminarán <span class="font-medium text-white">{{ count($selectedAppointmentIds) }} cita(s)</span>.
      </p>
      <p class="mt-2 text-sm text-slate-400">Esta acción no se puede deshacer.</p>

      <x-slot:actions>
        <x-botones.icono-buton color="amber" icon="volver" label="Cancelar" texto="Cancelar"
                               wire:click="$set('bulkDeleteConfirmationOpen', false)"/>
        <x-botones.icono-buton color="red" icon="papelera" label="Eliminar citas" texto="Eliminar citas"
                               wire:click="deleteSelected"/>
      </x-slot:actions>
    </x-modales.confirmacion>
  @endif
  {{-- MODAL HISTORIAL DE COMUNICACIONES --}}
  <x-modales.historia-whatsapp :historyAppointment="$historyAppointment"
                               wire:key="history-modal-{{ $historyAppointment?->id }}"/>

</div>
