<div>
  @if ($appointmentPendingResend)
    <div role="dialog" aria-modal="true"
            {{ $attributes->class(['fixed inset-0 z-[9999] grid place-items-center px-4 py-6']) }}>
      <div class="absolute inset-0 bg-slate-950/80" aria-hidden="true"></div>
      <div class="relative z-10 w-full max-w-md rounded-3xl border border-white/10 bg-slate-900 p-6 shadow-2xl">
        <h3 class="text-lg font-semibold">{{ $titulo }}</h3>
        <p class="mt-3 text-sm text-slate-300">
          ¿Seguro que quieres reenviar la cita de
          <span class="font-medium text-white">{{ $appointmentPendingResend->client?->full_name }}</span>
          del {{ $appointmentPendingResend->fecha?->format('d/m/Y') }} a las {{ Str::substr($appointmentPendingResend->hora, 0, 5) }}?
        </p>
        <div class="mt-6 flex flex-wrap justify-end gap-2">
          <x-botones.icono-buton color="amber" label="Cancelar" texto="Cancelar" icon="volver"
                                 wire:click="cancelDelete"/>
          <x-botones.icono-buton color="red" icon="papelera" label="Eliminar cita" texto="Eliminar cita"
                                 wire:click="deleteConfirmed"/>
        </div>
      </div>
    </div>
  @endif
</div>
