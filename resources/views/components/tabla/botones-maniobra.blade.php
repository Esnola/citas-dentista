<td class="px-4 py-3 text-right" onclick="event.stopPropagation()">
  @php
    $isPastOrToday = $appointment->fecha?->toDateString() <= now(config('app.timezone'))->toDateString();
    $whatsAppDisabled = $isPastOrToday || ! $appointment->activo || ! $appointment->cita_activa;
  @endphp
  <div class="flex justify-end items-center gap-2">
    <x-botones.icono-buton
            color="emerald"
            icon="whatsapp"
            label="Enviar WhatsApp"
            wire:click="sendNow({{ $appointment->id }})"
            wire:loading.attr="disabled"
            wire:target="sendNow({{ $appointment->id }})"
            :disabled="$whatsAppDisabled"
    />
    <x-botones.icono-buton
            color="blue"
            icon="editar-cita"
            label="Editar cita"
            onclick="window.location='{{ $editUrl }}'"
            :disabled="$isPastOrToday"
    />
  </div>
</td>
