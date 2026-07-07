<div class="rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur">
  <div class="flex flex-wrap items-start justify-between gap-4">
    <div>
      <h3 class="text-lg font-semibold">Credenciales Twilio</h3>
      <p class="mt-2 text-sm text-slate-300">
        Configura el modo de envío y las credenciales de acceso.
      </p>
    </div>
  </div>

  @if ($status)
    <div class="mt-4 rounded-2xl border border-emerald-400/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
      {{ $status }}
    </div>
  @endif

  <form class="mt-6 grid gap-5" wire:submit="save">
    <div class="grid gap-4 md:grid-cols-2">
      <div class="rounded-2xl border border-white/10 bg-slate-900/50 p-4">
        <h4 class="text-sm font-semibold text-slate-200">Modo de envío</h4>
        <p class="mt-1 text-xs text-slate-400">Sandbox para pruebas, Sender para producción.</p>

        <div class="mt-4 flex items-center gap-4">
          <x-formularios.toggle
                  wire:model="mode"
                  wire:change="$dispatch('modeChanged', { value: mode })"
                  texto="{{ $mode === 'sandbox' ? 'Sandbox' : 'Sender' }}" />
        </div>

        <div class="mt-3 text-xs text-slate-400">
          @if ($mode === 'sandbox')
            <span class="text-amber-400">Sandbox</span> — Solo envía a números registrados en el sandbox de Twilio.
          @else
            <span class="text-emerald-400">Sender</span> — Envía a cualquier número con un remitente registrado.
          @endif
        </div>
      </div>

      <div class="rounded-2xl border border-white/10 bg-slate-900/50 p-4">
        <h4 class="text-sm font-semibold text-slate-200">API Key (Opcional)</h4>
        <p class="mt-1 text-xs text-slate-400">Si se configura, se usa en lugar de Auth Token.</p>

        <div class="mt-4 space-y-3">
          <div>
            <flux:input wire:model="api_key_sid" label="API Key SID" placeholder="SK..." />
            <flux:error name="api_key_sid"/>
          </div>
          <div>
            <flux:input wire:model="api_key_secret" label="API Key Secret" type="password" placeholder="••••••••" />
            <flux:error name="api_key_secret"/>
          </div>
        </div>
      </div>
    </div>

    <div class="rounded-2xl border border-white/10 bg-slate-900/50 p-4">
      <h4 class="text-sm font-semibold text-slate-200">Remitente</h4>
      <p class="mt-1 text-xs text-slate-400">Número de teléfono de envío. En modo sandbox usa el número por defecto de Twilio.</p>

      <div class="mt-4 grid gap-4 md:grid-cols-2">
        <div>
          <flux:input wire:model="from_number" label="Número de remitente" placeholder="whatsapp:+14155238886" />
          <flux:error name="from_number"/>
        </div>
        <div>
          <flux:input wire:model="test_recipient" label="Destinatario de prueba" placeholder="whatsapp:+34600000000" />
          <flux:error name="test_recipient"/>
        </div>
      </div>
    </div>

    <div class="rounded-2xl border border-white/10 bg-slate-950/40 p-4 text-sm text-slate-300">
      @if ($credential->api_key_sid)
        <span class="text-emerald-400">✓</span> API Key configurada.
      @else
        <span class="text-slate-500">—</span> Sin API Key. Se usa Auth Token del servidor.
      @endif
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
