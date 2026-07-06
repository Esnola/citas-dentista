<div class="grid gap-6">
  @if ($status)
    <div class="rounded-2xl border border-emerald-400/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
      {{ $status }}
    </div>
  @endif

  <div class="grid gap-6 lg:grid-cols-2">
    <form class="grid content-start gap-4 rounded-2xl border border-white/10 bg-slate-900/50 p-4" wire:submit="addTemplate">
      <flux:field>
        <flux:label>Nombre</flux:label>
        <x-formularios.input wire:model="nombre" placeholder="Recordatorio de cita"/>
        <flux:error name="nombre"/>
      </flux:field>

      <flux:field>
        <flux:label>Content SID</flux:label>
        <x-formularios.input wire:model="contentSid" placeholder="HX..."/>
        <flux:error name="contentSid"/>
      </flux:field>

      <div>
        <flux:button type="submit" variant="primary">Guardar plantilla</flux:button>
      </div>
    </form>

    <div class="grid content-start gap-3">
      @if ($templates->isNotEmpty())
        @foreach ($templates as $template)
          <div wire:key="twilio-template-{{ $template->id }}"
               class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-white/10 bg-slate-900/50 p-4">
            <div>
              <p class="font-medium text-slate-100">
                {{ $template->nombre }}
                @if ($template->seleccionada)
                  <flux:badge color="emerald" size="sm">En uso</flux:badge>
                @endif
              </p>
              <p class="mt-1 font-mono text-sm text-slate-400">{{ $template->content_sid }}</p>
            </div>
            <div class="flex flex-wrap gap-2">
              @unless ($template->seleccionada)
                <flux:button type="button" size="sm" wire:click="selectTemplate({{ $template->id }})">
                  Usar plantilla
                </flux:button>
              @endunless
              <flux:button type="button" variant="danger" size="sm"
                           wire:click="deleteTemplate({{ $template->id }})"
                           wire:confirm="¿Eliminar esta plantilla?">
                Eliminar
              </flux:button>
            </div>
          </div>
        @endforeach
      @else
        <p class="rounded-2xl border border-white/10 bg-slate-900/50 p-4 text-sm text-slate-300">
          Aún no hay plantillas guardadas. Mientras tanto se usa {{ $envContentSid ?: 'ningún Content SID del .env' }}.
        </p>
      @endif
    </div>
  </div>
</div>
