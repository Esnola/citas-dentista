@php
  $selectedNumber = $credential->selectedSenderNumber();
@endphp

<div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
  <div class="rounded-2xl border border-white/10 bg-slate-900/60 p-4">
    <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Driver</p>
    <p class="mt-2 font-medium">{{ $driver }}</p>
    <p class="mt-1 text-sm text-slate-300">
      @if ($driver === 'twilio')
        Twilio WhatsApp
      @elseif ($driver === 'cloud_api')
        Meta WhatsApp Cloud API
      @else
        Modo local / registro
      @endif
    </p>
  </div>

  <div class="rounded-2xl border border-white/10 bg-slate-900/60 p-4">
    <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Plantilla por defecto</p>
    <p class="mt-2 font-medium">{{ config('whatsapp.default_template') }}</p>
    <p class="mt-1 text-sm text-slate-300">{{ config('whatsapp.default_message') ?? config('whatsapp.templates.' . config('whatsapp.default_template') . '.message') }}</p>
  </div>

  <div class="rounded-2xl border border-white/10 bg-slate-900/60 p-4">
    <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Twilio</p>
    <p class="mt-2 font-medium">
      {{ $credential->api_key_sid || $selectedNumber ? 'Credenciales listas' : 'Credenciales pendientes' }}
    </p>
    <p class="mt-1 text-sm text-slate-300">
      {{ $selectedNumber ? 'Canal configurado' : 'Falta el canal de envío' }}
      @if ($twilioUsesTemplate)
        · {{ $twilioContentSid ? 'plantilla configurada' : 'falta Content SID' }}
      @endif
    </p>
  </div>

  <div class="rounded-2xl border border-white/10 bg-slate-900/60 p-4">
    <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Modo Twilio</p>
    <p class="mt-2 font-medium">{{ $credential->mode }} → {{ $resolvedMode }}</p>
    <p class="mt-1 text-sm text-slate-300">
      {{ $selectedNumber && $selectedNumber->whatsapp_address === 'whatsapp:+14155238886' ? 'Sandbox detectado por el remitente' : 'El envío real usará el modo resuelto' }}
    </p>
  </div>
</div>
