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
