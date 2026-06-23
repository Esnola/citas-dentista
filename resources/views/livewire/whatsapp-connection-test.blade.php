<div class="rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur">
    <div class="flex items-start justify-between gap-4">
        <div>
            <h3 class="text-lg font-semibold">Prueba de conexión</h3>
            <p class="mt-2 text-sm text-slate-300">
                Envía un mensaje corto al número que indiques para comprobar que Twilio responde.
            </p>
        </div>
        <div class="rounded-2xl border border-white/10 bg-slate-900/60 px-4 py-3 text-xs uppercase tracking-[0.25em] text-slate-300">
            Sandbox-friendly
        </div>
    </div>

    @if ($status)
        <div class="mt-4 rounded-2xl border px-4 py-3 text-sm
            @if ($statusType === 'success')
                border-emerald-400/30 bg-emerald-500/10 text-emerald-200
            @elseif ($statusType === 'error')
                border-rose-400/30 bg-rose-500/10 text-rose-200
            @else
                border-white/10 bg-slate-900/60 text-slate-200
            @endif
        ">
            {{ $status }}
        </div>
    @endif

    <div class="mt-4 grid gap-4 md:grid-cols-3">
        <div class="rounded-2xl border border-white/10 bg-slate-900/50 p-4">
            <p class="text-xs uppercase tracking-[0.25em] text-slate-500">Driver</p>
            <p class="mt-2 font-medium text-slate-100">{{ $previewPayload['provider'] ?? 'n/a' }}</p>
        </div>
        <div class="rounded-2xl border border-white/10 bg-slate-900/50 p-4">
            <p class="text-xs uppercase tracking-[0.25em] text-slate-500">Modo</p>
            <p class="mt-2 font-medium text-slate-100">{{ $previewPayload['mode'] ?? 'n/a' }}</p>
        </div>
        <div class="rounded-2xl border border-white/10 bg-slate-900/50 p-4">
            <p class="text-xs uppercase tracking-[0.25em] text-slate-500">Destino</p>
            <p class="mt-2 font-medium text-slate-100">{{ $previewPayload['request']['To'] ?? $previewPayload['request']['to'] ?? $previewPayload['request']['recipient'] ?? 'n/a' }}</p>
        </div>
    </div>

    <form class="mt-6 grid gap-4" wire:submit="sendTest">
        <div class="grid gap-4 md:grid-cols-3">
            <flux:field>
                <flux:label>Modo</flux:label>
                <flux:select wire:model="mode">
                    <option value="sandbox">Sandbox</option>
                    <option value="sender">Número real</option>
                    <option value="service">Messaging Service</option>
                </flux:select>
                <flux:error name="mode" />
            </flux:field>

            <flux:field>
                <flux:label>Destino</flux:label>
                <flux:input wire:model="recipient" placeholder="whatsapp:+34600123123 o 600123123" />
                <flux:error name="recipient" />
            </flux:field>

            <flux:field>
                <flux:label>Mensaje</flux:label>
                <flux:textarea wire:model="body" rows="4" />
                <flux:error name="body" />
            </flux:field>
        </div>

        <div class="rounded-2xl border border-white/10 bg-slate-900/50 p-4 text-sm text-slate-300">
            <p class="font-medium text-slate-200">Notas de prueba</p>
            <ul class="mt-2 space-y-1">
                <li>• `Sandbox` usa el sender de pruebas configurado en `TWILIO_WHATSAPP_FROM`.</li>
                <li>• `Número real` usa el remitente de producción que configures en Twilio.</li>
                <li>• `Messaging Service` envía usando `TWILIO_MESSAGING_SERVICE_SID`.</li>
                <li>• Si usas un número local, se normaliza a formato internacional.</li>
            </ul>
        </div>

        <div class="rounded-2xl border border-white/10 bg-slate-950/50 p-4">
            <div class="flex items-center justify-between gap-3">
                <p class="text-sm font-medium text-slate-200">Vista previa del payload</p>
                <span class="text-xs uppercase tracking-[0.25em] text-slate-500">Antes de enviar</span>
            </div>
            <pre class="mt-3 overflow-x-auto rounded-xl border border-white/10 bg-slate-950/80 p-4 text-xs leading-5 text-slate-200">{{ json_encode($previewPayload['request'] ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
        </div>

        <div class="flex flex-wrap gap-3">
            <flux:button type="submit">Enviar prueba</flux:button>
            <flux:button type="button" wire:click="sendSavedRecipient">Enviar al guardado</flux:button>
        </div>
    </form>

    @if (! empty($details))
        <div class="mt-6 rounded-2xl border border-white/10 bg-slate-950/40 p-4 text-sm text-slate-300">
            <p class="font-medium text-slate-200">Respuesta</p>
            <div class="mt-3 grid gap-2 md:grid-cols-4">
                <div>
                    <p class="text-xs uppercase tracking-[0.25em] text-slate-500">Proveedor</p>
                    <p class="mt-1">{{ $details['provider'] ?? 'n/a' }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-[0.25em] text-slate-500">ID</p>
                    <p class="mt-1">{{ $details['message_id'] ?? 'n/a' }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-[0.25em] text-slate-500">Destino</p>
                    <p class="mt-1">{{ $details['to'] ?? 'n/a' }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-[0.25em] text-slate-500">Modo</p>
                    <p class="mt-1">{{ $details['mode'] ?? 'n/a' }}</p>
                </div>
            </div>
        </div>
    @endif
</div>
