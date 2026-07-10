<td class="px-4 py-3 text-right" onclick="event.stopPropagation()">
  <div class="flex justify-end items-center gap-2">

    @if (! $appointment->enviado && $appointment->activo && $appointment->scheduledFor()->isFuture())
      <x-botones.icono-buton
              color="emerald"
              icon="whatsapp"
              label="Enviar WhatsApp"
              wire:click="sendNow({{ $appointment->id }})"
              wire:loading.attr="disabled"
              wire:target="sendNow({{ $appointment->id }})"
      />
    @endif
    <x-botones.icono-buton
            color="blue"
            icon="lapiz"
            label="Editar cita"
            onclick="window.location='{{ $editUrl }}'"
    />
    <x-botones.icono-buton
            color="red"
            icon="papelera"
            label="Eliminar cita"
            wire:click="confirmDelete({{ $appointment->id }})"
    />
  </div>
</td>
