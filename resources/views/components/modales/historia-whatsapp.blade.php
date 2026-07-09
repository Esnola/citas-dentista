@if ($historyAppointment)
  <div x-data="{ modalOpen: true }" x-trap.noscroll="modalOpen"
       x-on:keydown.escape.window="$wire.closeHistory()"
       class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm"
       x-show="modalOpen" x-transition.opacity>
    <div class="relative mx-4 w-full max-w-2xl max-h-[80vh] overflow-hidden rounded-2xl border border-white/10 bg-slate-900 shadow-2xl"
         x-show="modalOpen" x-transition.scale.95>
      <div class="flex items-center justify-between border-b border-white/10 px-6 py-4">
        <div>
          <h3 class="text-lg font-semibold text-white">Historial de comunicaciones</h3>
          <p class="text-sm text-slate-400">
            {{ $historyAppointment->client?->full_name }} —
            {{ $historyAppointment->fecha?->format('d/m/Y') }} {{ Str::substr($historyAppointment->hora, 0, 5) }}
          </p>
        </div>
        <button wire:click="closeHistory"
                class="rounded-lg p-1 text-slate-400 hover:text-white transition-colors cursor-pointer">
          <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>
      <div class="overflow-y-auto p-6 space-y-3" style="max-height: calc(80vh - 80px)">
        @forelse ($historyAppointment->whatsAppMessages as $msg)
          @php $isInbound = $msg->direction === 'inbound';
            $laClase= $isInbound
                    ? 'bg-whatsapp border border-emerald-500/20'
                    : 'bg-slate-800/60 border border-white/10'

          @endphp
          <div class="flex {{ !$isInbound ? 'justify-end' : 'justify-start' }}">
            <div class="max-w-[75%] px-4 py-3 {{ $laClase }}">
              <div class="flex items-center gap-2 mb-1">
                  <span class="text-[10px] font-semibold {{ $isInbound ? 'text-emerald-700' : 'text-slate-400' }}">
                    {{ $isInbound ? 'Recibido' : $msg->sent_at?->format('d/m/Y H:i:s') }}
                  </span>
                <span class="text-[10px] text-slate-800">
                    {{ $msg->sent_at?->format('d/m/Y H:i:s') }}
                  </span>
              </div>
              @if ($isInbound && $msg->respuesta)
                <div class="mt-1 flex items-center gap-1">
                  @php
                    $btnPayload = strtolower(trim((string) data_get($msg->provider_payload, 'inbound.button_payload', '')));
                    $isConfirm = str_starts_with($btnPayload, 'confirm');
                     $laClase = match(true) {
                      $btnPayload && $isConfirm => ['clase' => 'cita-confirmada', 'texto' => 'Confirmada', 'icono' => 'usuario-plus'],
                      $btnPayload && !$isConfirm => ['clase' => 'cambiar-cita', 'texto' => 'Cambiar cita', 'icono' => 'alert'],
                      default => ['clase' => 'text-slate-600', 'texto' =>null, 'icono' => null],
                      }
                  @endphp
                  <span class="inline-flex items-center rounded-full px-2 py-0.5 text-sm
                        {{ $laClase['clase'] }}">
                      @if ($laClase['icono'])
                      <x-dynamic-component :component="'iconos.' . $laClase['icono']" class="size-2 mr-1"/>
                    @endif
                    {{ $laClase['texto'] ??  $msg->respuesta }}
                    </span>
                </div>
              @endif
              @if (! $isInbound)
                @php $delivery = $msg->deliveryStatus(); @endphp
                <div class="mt-1 flex items-center gap-1">

                  @if ($delivery === 'read')
                    <x-iconos.doble-check clase="size-4 text-green-400"/>
                    <span class="text-[10px] text-slate-500">Leído</span>
                  @elseif ($delivery === 'delivered')
                    <x-iconos.doble-check clase="size-4 text-slate-400"/>
                    <span class="text-[10px] text-slate-500">Entregado</span>
                  @elseif ($msg->status === 'sent')
                    <x-iconos.whatsapp clase="size-4 text-slate-500"/>
                    <span class="text-[10px] text-slate-500">Enviado</span>
                  @elseif ($msg->status === 'failed')
                    <x-iconos.alert clase="size-4 text-red-400"/>
                    <span class="text-[10px] text-slate-500">Fallido</span>
                  @endif
                </div>
              @endif
            </div>
          </div>
        @empty
          <div class="py-8 text-center text-slate-500">
            No hay mensajes registrados para esta cita.
          </div>
        @endforelse
      </div>
    </div>
  </div>
@endif
