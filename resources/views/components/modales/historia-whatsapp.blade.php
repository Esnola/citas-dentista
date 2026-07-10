@if ($historyAppointment)
  <div x-data="{ modalOpen: true }" x-trap.noscroll="modalOpen"
       x-on:keydown.escape.window="$wire.closeHistory()"
       class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm"
       x-show="modalOpen" x-transition.opacity>
    <div class="relative mx-4 flex max-h-[80vh] w-full max-w-2xl flex-col overflow-hidden rounded-2xl border border-white/10 bg-slate-900 shadow-2xl"
         x-show="modalOpen" x-transition.scale.95>
      <div class="shrink-0 flex items-center justify-between border-b border-white/10 px-6 py-4">
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
      <div class="min-h-0 flex-1 overflow-y-auto p-6 space-y-3">
        @forelse ($historyAppointment->whatsAppMessages as $msg)
          @php
            $isInbound = $msg->direction === 'inbound';
            $laClase = $isInbound
                    ? 'bg-whatsapp border border-emerald-500/20'
                    : 'bg-slate-800/60 border border-white/10';
            $displayMessage = $msg->message ?: $msg->responseValue();
            $buttonPayload = $isInbound
                    ? strtolower(trim((string) data_get($msg->provider_payload, 'inbound.button_payload', '')))
                    : '';
            $buttonBadge = match (true) {
                    str_starts_with($buttonPayload, 'confirm') => ['clase' => 'cita-confirmada', 'texto' => 'Confirmada', 'icono' => 'usuario-plus'],
                    str_starts_with($buttonPayload, 'reprogram'), str_starts_with($buttonPayload, 'cambiar') => ['clase' => 'cambiar-cita', 'texto' => 'Cambiar cita', 'icono' => 'alert'],
                    default => null,
            };
          @endphp
          <div class="flex {{ !$isInbound ? 'justify-end' : 'justify-start' }}">
            <div class="max-w-full sm:max-w-[85%] px-4 py-3 {{ $laClase }}">
              <div class="mb-1 flex flex-wrap items-center gap-x-2 gap-y-1">
                  <span class="text-[10px] font-semibold {{ $isInbound ? 'text-emerald-700' : 'text-slate-400' }}">
                    {{ $isInbound ? 'Recibido' : 'Enviado' }}
                  </span>
                <span class="text-[10px] {{ $isInbound ? 'text-emerald-800' : 'text-slate-500' }}">
                    {{ $msg->sent_at?->format('d/m/Y H:i:s') }}
                  </span>
              </div>
              @if (! $buttonBadge && filled($displayMessage))
                <p class="mt-2 whitespace-pre-wrap break-words text-sm leading-relaxed {{ $isInbound ? 'text-slate-950' : 'text-slate-100' }}">{{ $displayMessage }}</p>
              @endif
              @if ($buttonBadge)
                <div class="mt-2 flex items-center gap-1">
                  <span class="inline-flex items-center rounded-full px-2 py-0.5 text-sm {{ $buttonBadge['clase'] }}">
                    <x-dynamic-component :component="'iconos.' . $buttonBadge['icono']" class="size-2 mr-1"/>
                    {{ $buttonBadge['texto'] }}
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
