<div class="rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur">
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
      <div class="rounded-2xl border border-white/10 bg-slate-900/50 p-4">
        <flux:checkbox.group wire:model="whatsappLeadDays" label="WhatsApp">
          <div class="mt-3 grid gap-3">
            @foreach ($leadDayOptions as $leadDays => $label)
              <flux:checkbox
                      value="{{ $leadDays }}"
                      label="{{ $label }}"
              />
            @endforeach
          </div>
        </flux:checkbox.group>
        <flux:error name="whatsappLeadDays"/>
        <flux:error name="whatsappLeadDays.*"/>
      </div>

      <div class="rounded-2xl border border-white/10 bg-slate-900/50 p-4">
        <flux:checkbox.group wire:model="emailLeadDays" label="Email">
          <div class="mt-3 grid gap-3">
            @foreach ($leadDayOptions as $leadDays => $label)
              <flux:checkbox
                      value="{{ $leadDays }}"
                      label="{{ $label }}"
              />
            @endforeach
          </div>
        </flux:checkbox.group>
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
          <flux:checkbox.group wire:model="dispatchHours" label="Horas de envío">
            <div class="mt-3 grid grid-cols-3 gap-3 sm:grid-cols-4 md:grid-cols-5">
              @foreach ($availableHours as $hour)
                <flux:checkbox
                        value="{{ $hour }}"
                        label="{{ $hour }}"
                />
              @endforeach
            </div>
          </flux:checkbox.group>
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
              texto="Guardar" />
    </div>
  </form>
</div>
