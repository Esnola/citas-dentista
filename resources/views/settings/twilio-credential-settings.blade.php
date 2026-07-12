<div class="rounded-3xl border border-white/10 p-6">
  <div class="flex flex-wrap items-start justify-between gap-4">
    <div>
      <p class="mt-2 text-sm text-slate-300">
        Configura el modo de envío y las credenciales de acceso.
      </p>
    </div>
  </div>

  @if ($status)
    @php
      $isError = str_contains($status, 'Error')
        || str_contains($status, 'error')
        || str_contains($status, 'Faltan')
        || str_contains($status, 'No se pudo')
        || str_contains($status, 'no es válida')
        || str_contains($status, 'HTTP 4')
        || str_contains($status, 'HTTP 5');
    @endphp
    @if ($isError)
      <div class="mt-4 rounded-2xl border border-rose-400/30 bg-rose-500/10 px-4 py-3 text-sm text-rose-200 flex items-start gap-2">
        <x-iconos.alert class="mt-0.5 h-4 w-4 shrink-0 text-rose-400"/>
        <span class="whitespace-pre-wrap">{{ $status }}</span>
      </div>
    @else
      <div class="mt-4 rounded-2xl border border-emerald-400/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200 flex items-start gap-2">
        <x-iconos.check class="mt-0.5 h-4 w-4 shrink-0 text-emerald-400"/>
        <span class="whitespace-pre-wrap">{{ $status }}</span>
      </div>
    @endif
  @endif

  <form class="mt-6 grid gap-5" wire:submit="save">
    <div class="grid gap-4 md:grid-cols-3">
      <div class="rounded-2xl border border-white/10 bg-slate-900/50 p-4">
        <h4 class="text-sm font-semibold text-slate-200">Modo de envío</h4>
        <p class="mt-1 text-xs text-slate-400"><span class="underline text-emerald-400/50">Sandbox</span> para pruebas,
          <span class="underline text-emerald-400/50">Sender</span> para producción.</p>

        <div class="mt-4 flex items-center gap-4">
          <x-formularios.toggle :checked="$mode === 'sender'" wire:click="toggleMode"
                                texto="{{ $mode === 'sandbox' ? 'Sandbox' : 'Sender' }}"/>
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
        <h4 class="text-sm font-semibold text-slate-200">Callback URL</h4>
        <p class="mt-1 text-xs text-slate-400">URL donde Twilio envía el estado de entrega del mensaje.</p>

        <div class="mt-4">
          <flux:input wire:model="status_callback_url" label="CALLBACK URL"
                      placeholder="https://ejemplo.com/webhooks/twilio/whatsapp-status"/>
          <flux:error name="status_callback_url"/>
        </div>

        <div class="mt-3 flex items-center gap-3">
          <button type="button" wire:click="testWebhook"
                  class="text-xs text-slate-400 hover:text-emerald-400 transition-colors underline">
            Probar endpoint
          </button>
        </div>
        <p class="mt-2 text-xs text-slate-500">
          Esta URL se envía a Twilio como <code>StatusCallback</code> en cada mensaje nuevo.
        </p>
      </div>

      <div class="rounded-2xl border border-white/10 bg-slate-900/50 p-4">
        <h4 class="text-sm font-semibold text-slate-200">Sincronización</h4>
        <p class="mt-1 text-xs text-slate-400">Configura cómo se reciben las respuestas de WhatsApp.</p>

        <div class="mt-4 flex items-center gap-4">
          <x-formularios.toggle :checked="$webhook_enabled" wire:click="$set('webhook_enabled', !{{ $webhook_enabled ? 'true' : 'false' }})"
                                texto="{{ $webhook_enabled ? 'Webhook activado' : 'Webhook desactivado' }}"/>
        </div>

        <div class="mt-3 text-xs text-slate-400">
          @if ($webhook_enabled)
            <span class="text-emerald-400">Webhook</span> — Los mensajes nuevos incluyen <code>StatusCallback</code> para recibir eventos en este endpoint.
            La interfaz hace un refresco ligero desde base de datos cada 2 segundos, sin volver a consultar Twilio.
          @else
            <span class="text-amber-400">Polling</span> — No se enviará <code>StatusCallback</code> y la app consultará Twilio periódicamente.
          @endif
        </div>

        @if (! $webhook_enabled)
          <div class="mt-4">
            <flux:input wire:model="poll_interval" label="Intervalo de sincronización (segundos)" type="number" min="5" max="60"
                        placeholder="10"/>
            <flux:error name="poll_interval"/>
            <p class="mt-1 text-xs text-slate-500">Entre 5 y 60 segundos. Se consulta la API de Twilio cada este intervalo.</p>
          </div>
        @endif
      </div>
    </div>

    <div class="grid gap-4 md:grid-cols-2">
      <div class="rounded-2xl border border-white/10 space-y-3 bg-slate-900/50 p-4">
        <div>
          <flux:input wire:model="api_key_sid" :label="$apiKeySidLabel" placeholder="SK..."/>
          <flux:error name="api_key_sid"/>
        </div>
        <div>
          <flux:input wire:model="api_key_secret" :label="$apiKeySecretLabel" type="password" placeholder="••••••••"/>
          <flux:error name="api_key_secret"/>
        </div>
      </div>
    </div>

    <div class="rounded-2xl border border-white/10 bg-slate-900/50 p-4">
      <h4 class="text-sm font-semibold text-slate-200">Números de remitente</h4>
      <p class="mt-1 text-xs text-slate-400">El número activo se usará como remitente en Twilio. Se añade
        automáticamente `whatsapp:`.</p>

      <div class="mt-4 flex flex-col gap-3 md:flex-row">
        <div class="w-1/3">
          @forelse ($senderNumbers as $number)
            <div class="flex items-center gap-3 rounded-xl border border-white/10 bg-slate-950/40 px-4 py-3">
              <input type="radio" name="selected_sender" wire:click="selectSenderNumber({{ $number['id'] }})"
                     {{ $number['selected'] ? 'checked' : '' }} class="h-4 w-4 border-slate-600 bg-slate-700 text-emerald-500 focus:ring-emerald-500/50"/>

              <div class="flex-1 min-w-0">
                <p class="text-sm text-slate-200 truncate">
                  {{ $number['name'] ?: $number['prefix'] . $number['number'] }}
                </p>
                <p class="text-xs text-slate-500 font-mono">{{ $number['prefix'] }}{{ $number['number'] }}</p>
              </div>

              <button type="button" wire:click="confirmRemoveSenderNumber({{ $number['id'] }})"
                      class="shrink-0 text-slate-500 hover:text-rose-400 transition-colors">
                <x-iconos.borrar class="h-4 w-4"/>
              </button>
            </div>
          @empty
            <p class="col-span-2 text-sm text-slate-500">No hay números configurados. Añade uno abajo.</p>
          @endforelse
        </div>
        <div class="mt-4 w-full">
          <div class="flex md:grid grid-cols-3 gap-4 items-center w-full">
            <flux:input wire:model="newName" label="Nombre (identificador)" placeholder="Ej: Consulting Room"/>
            <div>
              <label class="text-xs text-slate-400">Prefijo</label>
              <x-formularios.select wire:model="newPrefix" class="mt-1">
                <option value="+1">USA/Canadá (+1)</option>
                <option value="+34">España (+34)</option>
                {{--     <option value="+52">México (+52)</option>
                     <option value="+54">Argentina (+54)</option>
                     <option value="+56">Chile (+56)</option>
                     <option value="+57">Colombia (+57)</option>
                     <option value="+51">Perú (+51)</option>
                     <option value="+44">Reino Unido (+44)</option>--}}
              </x-formularios.select>
            </div>
            <flux:input wire:model="newNumber" label="Número" placeholder="600000000"/>
            <flux:error name="newNumber"/>
          </div>
          <button type="button" wire:click="addSenderNumber"
                  class="m-6 shrink-0 rounded-xl border border-emerald-400/30 bg-emerald-500/10 px-4 py-2.5 text-sm text-emerald-300 hover:bg-emerald-500/20 transition-colors">
            Añadir
          </button>
        </div>
      </div>
    </div>

    @if ($pendingSenderNumber)
      <x-modales.confirmacion x-data="{ modalOpen: true }" x-trap.noscroll="modalOpen"
                              x-on:keydown.escape.window="$wire.cancelRemoveSenderNumber()"
                              titulo="Eliminar número de remitente">
        <p class="mt-4 text-sm text-slate-300">
          ¿Seguro que quieres eliminar
          <span class="font-medium text-white">
                        {{ $pendingSenderNumber['name'] ?: $pendingSenderNumber['prefix'] . $pendingSenderNumber['number'] }}
                    </span>?
        </p>

        <x-slot:actions>
          <x-botones.icono-buton label="Cancelar" texto="Cancelar" x-on:click="$wire.cancelRemoveSenderNumber()"/>
          <x-botones.icono-buton color="red" icon="papelera" label="Eliminar número" texto="Eliminar"
                                 wire:click="removeSenderNumber({{ $pendingSenderNumber['id'] }})"/>
        </x-slot:actions>
      </x-modales.confirmacion>
    @endif

    <div>
      <x-botones.icono-buton icon="disquete" type="submit" label="Guardar" texto="Guardar"/>
    </div>
  </form>
</div>
