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
                    <x-formularios.toggle :checked="$mode === 'sender'" wire:click="toggleMode" texto="{{ $mode === 'sandbox' ? 'Sandbox' : 'Sender' }}" />
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
                    <flux:input wire:model="status_callback_url" label="CALLBACK URL" placeholder="https://ejemplo.com/webhooks/twilio/whatsapp-status" />
                    <flux:error name="status_callback_url" />
                </div>
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <div class="rounded-2xl border border-white/10 bg-slate-900/50 p-4">
                <h4 class="text-sm font-semibold text-slate-200">API Key (Opcional)</h4>
                <p class="mt-1 text-xs text-slate-400">Si se configura, se usa en lugar de Auth Token.</p>

                <div class="mt-4 space-y-3">
                    <div>
                        <flux:input wire:model="api_key_sid" label="API Key SID" placeholder="SK..." />
                        <flux:error name="api_key_sid" />
                    </div>
                    <div>
                        <flux:input wire:model="api_key_secret" label="API Key Secret" type="password" placeholder="••••••••" />
                        <flux:error name="api_key_secret" />
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-white/10 bg-slate-900/50 p-4">
                <h4 class="text-sm font-semibold text-slate-200">Estado</h4>
                <div class="mt-4 space-y-2 text-sm text-slate-300">
                    @if ($credential->api_key_sid)
                    <p><span class="text-emerald-400">✓</span> API Key configurada.</p>
                    @else
                    <p><span class="text-slate-500">—</span> Sin API Key. Se usa Auth Token del servidor.</p>
                    @endif

                    @php $selectedNumber = $credential->selectedSenderNumber(); @endphp
                    @if ($selectedNumber)
                    <p><span class="text-emerald-400">✓</span> Remitente: {{ $selectedNumber->full_number }}</p>
                    @else
                    <p><span class="text-slate-500">—</span> Sin número de remitente configurado.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-white/10 bg-slate-900/50 p-4">
            <h4 class="text-sm font-semibold text-slate-200">Números de remitente</h4>
            <p class="mt-1 text-xs text-slate-400">El número activo se usará como remitente en Twilio. Se añade automáticamente `whatsapp:`.</p>

            <div class="mt-4 flex flex-col gap-3 md:flex-row">
                <div class="w-1/3">
                    @forelse ($senderNumbers as $number)
                    <div class="flex items-center gap-3 rounded-xl border border-white/10 bg-slate-950/40 px-4 py-3">
                        <input type="radio" name="selected_sender" wire:click="selectSenderNumber({{ $number['id'] }})" {{ $number['selected'] ? 'checked' : '' }} class="h-4 w-4 border-slate-600 bg-slate-700 text-emerald-500 focus:ring-emerald-500/50" />

                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-slate-200 truncate">
                                {{ $number['name'] ?: $number['prefix'] . $number['number'] }}
                            </p>
                            <p class="text-xs text-slate-500 font-mono">{{ $number['prefix'] }}{{ $number['number'] }}</p>
                        </div>

                        <button type="button" wire:click="removeSenderNumber({{ $number['id'] }})" wire:confirm="¿Eliminar este número?" class="shrink-0 text-slate-500 hover:text-rose-400 transition-colors">
                            <x-iconos.borrar class="h-4 w-4" />
                        </button>
                    </div>
                    @empty
                    <p class="col-span-2 text-sm text-slate-500">No hay números configurados. Añade uno abajo.</p>
                    @endforelse
                </div>
                <div class="mt-4 w-full">
                    <div class="flex md:grid grid-cols-3 gap-4 items-center w-full">
                        <flux:input wire:model="newName" label="Nombre (identificador)" placeholder="Ej: Consulting Room" />
                        <div>
                            <label class="text-xs text-slate-400">Prefijo</label>
                            <input type="text" wire:model="newPrefix" list="prefix-options" placeholder="+1" class="w-full rounded-xl border border-white/10 bg-slate-950/40 px-3 py-2.5 text-sm text-slate-200 focus:border-emerald-400 focus:outline-none" />
                            <datalist id="prefix-options">
                                <option value="+1">USA/Canadá</option>
                                <option value="+34">España</option>
                                <option value="+52">México</option>
                                <option value="+54">Argentina</option>
                                <option value="+56">Chile</option>
                                <option value="+57">Colombia</option>
                                <option value="+51">Perú</option>
                                <option value="+44">Reino Unido</option>
                            </datalist>
                        </div>
                        <flux:input wire:model="newNumber" label="Número" placeholder="600000000" />
                        <flux:error name="newNumber" />

                    </div>
                    <button type="button" wire:click="addSenderNumber" class="m-6 shrink-0 rounded-xl border border-emerald-400/30 bg-emerald-500/10 px-4 py-2.5 text-sm text-emerald-300 hover:bg-emerald-500/20 transition-colors">
                        Añadir
                    </button>
                </div>
            </div>
        </div>

        <div>
            <x-botones.icono-buton icon="disquete" type="submit" label="Guardar" texto="Guardar" />
        </div>
    </form>
</div>
