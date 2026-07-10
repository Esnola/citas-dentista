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
