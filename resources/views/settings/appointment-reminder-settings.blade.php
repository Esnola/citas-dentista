<div class="rounded-3xl border border-white/10 p-6">
      <h3 class="text-lg font-semibold">Días de anticipación</h3>
      <p class="mt-1 text-sm text-slate-300">
        Configura los días de anticipación para enviar recordatorios a los clientes.

  @if ($status)
    <div class="mt-4 rounded-2xl border border-emerald-400/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
      {{ $status }}
    </div>
  @endif

  <form class="mt-6 grid gap-5" wire:submit="save">
    <div class="grid gap-4 md:grid-cols-2">
      <div class="rounded-2xl border border-white/10 p-4">
        <div>
          <h4 class="text-sm font-semibold uppercase tracking-[0.22em] text-emerald-200">WhatsApp</h4>
          <p class="mt-1 text-xs text-slate-400">Elige con cuánta antelación avisamos a cada cliente.</p>
          <div class="mt-4 grid gap-3">
            @foreach ($leadDayOptions as $leadDays => $label)
              <x-formularios.checkbox-card
                      wire:model="whatsappLeadDays"
                      value="{{ $leadDays }}"
                      :label="$label" />
            @endforeach
          </div>
        </div>
        <flux:error name="whatsappLeadDays"/>
        <flux:error name="whatsappLeadDays.*"/>
      </div>

      <div class="rounded-2xl border border-white/10 bg-slate-900/50 p-4">
        <div>
          <div class="flex items-center justify-between gap-3">
            <h4 class="text-sm font-semibold uppercase tracking-[0.22em] text-slate-200">Email</h4>
            <span class="rounded-full border border-rose-400/20 bg-rose-400/10 px-3 py-1 text-[11px] font-medium uppercase tracking-[0.24em] text-rose-200">
              Desactivado
            </span>
          </div>
          <p class="mt-1 text-xs text-slate-500">Reservado para cuando activemos el canal de correo.</p>
          <div class="mt-4 grid gap-3">
            @foreach ($leadDayOptions as $leadDays => $label)
              <x-formularios.checkbox-card
                      wire:model="emailLeadDays"
                      value="{{ $leadDays }}"
                      :label="$label"
                      disabled />
            @endforeach
          </div>
        </div>
        <flux:error name="emailLeadDays"/>
        <flux:error name="emailLeadDays.*"/>
      </div>
    </div>
    <div class="border-t border-white/10 pt-5">
      <h4 class="text-base font-semibold">Envíos programados</h4>
      <p class="mt-1 text-sm text-slate-300">
        Configura si se envían recordatorios automáticamente y a qué horas.
      </p>

      <div class="mt-4 rounded-2xl border border-white/10 bg-slate-900/50 p-4">
        <x-formularios.toggle
                wire:model.live="dispatchEnabled"
                wire:change="$dispatch('dispatchToggled', { value: $event.target.checked })"
                texto="Envío Automático" />

        <div class="mt-4" wire:ignore.self>
          <div>
            <h5 class="text-sm font-semibold uppercase tracking-[0.22em] text-emerald-200">Horas de envío</h5>
            <p class="mt-1 text-xs text-slate-400">Ventanas horarias en las que se lanzan los recordatorios.</p>
            <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-5">
              @foreach ($availableHours as $hour)
                <x-formularios.checkbox-card
                        wire:model="dispatchHours"
                        value="{{ $hour }}"
                        :label="$hour" />
              @endforeach
            </div>
          </div>
          <flux:error name="dispatchHours"/>
          <flux:error name="dispatchHours.*"/>
        </div>
      </div>
    </div>

    <div>
      <x-botones.icono-buton
              icon="disquete"
              type="submit"
              label="Guardar"
              texto="Guardar"
              especial="size-6"
      />
    </div>
  </form>
</div>
