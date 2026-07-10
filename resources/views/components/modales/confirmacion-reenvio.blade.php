<div>
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
</div>
