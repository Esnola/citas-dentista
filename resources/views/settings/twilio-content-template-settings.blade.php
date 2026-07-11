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

      <flux:radio.group wire:model="variablePreset" label="Variables de la plantilla">
        <div class="grid gap-3 pt-2">
          <flux:radio value="with_name" label='Nombre, día y hora · {"1":"[NOMBRE]","2":"[DIA]","3":"[HORA]"}'/>
          <flux:radio value="appointment" label='Día y hora · {"1":"[DIA]","2":"[HORA]"}'/>
        </div>
      </flux:radio.group>
      <flux:error name="variablePreset"/>

      <div>
        <x-botones.icono-buton
                icon="disquete"
                type="submit"
                label="Guardar plantilla"
                texto="Guardar plantilla" />
      </div>
    </form>

    <div class="grid content-start gap-3">
      @if ($templates->isNotEmpty())
        @php
          $selectedTemplate = $templates->firstWhere('seleccionada', true);
          $otherTemplates = $templates->reject(fn ($t) => $t->seleccionada);
        @endphp

        @if ($selectedTemplate)
          <div wire:key="twilio-template-selected-{{ $selectedTemplate->id }}"
               class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-emerald-400/50 bg-emerald-500/[0.03] p-4 transition-colors duration-150 hover:border-emerald-400/70 hover:bg-emerald-500/10">
            <div>
              <p class="font-medium text-slate-100">
                {{ $selectedTemplate->nombre }}
                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-500/15 px-2 py-0.5 text-xs font-semibold text-emerald-300 ring-1 ring-inset ring-emerald-400/40">
                  <x-iconos.check clase="size-3"/>
                  En uso
                </span>
              </p>
              <p class="mt-1 font-mono text-sm text-slate-400">{{ $selectedTemplate->content_sid }}</p>
              <p class="mt-1 font-mono text-xs text-slate-500">{{ json_encode($selectedTemplate->content_variables, JSON_UNESCAPED_SLASHES) }}</p>
            </div>
            <div class="flex flex-wrap gap-2">
              <x-botones.icono-buton
                      color="red"
                      icon="papelera"
                      label="Eliminar plantilla"
                      texto="Eliminar"
                      wire:click="confirmDeleteTemplate({{ $selectedTemplate->id }})" />
            </div>
          </div>
        @endif

        @foreach ($otherTemplates as $template)
          <div wire:key="twilio-template-{{ $template->id }}"
               class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-white/10 bg-slate-900/50 p-4">
            <div>
              <p class="font-medium text-slate-100">{{ $template->nombre }}</p>
              <p class="mt-1 font-mono text-sm text-slate-400">{{ $template->content_sid }}</p>
              <p class="mt-1 font-mono text-xs text-slate-500">{{ json_encode($template->content_variables, JSON_UNESCAPED_SLASHES) }}</p>
            </div>
            <div class="flex flex-wrap gap-2">
              <x-botones.icono-buton
                      icon="check"
                      label="Usar plantilla"
                      texto="Usar plantilla"
                      wire:click="selectTemplate({{ $template->id }})" />
              <x-botones.icono-buton
                      color="red"
                      icon="papelera"
                      label="Eliminar plantilla"
                      texto="Eliminar"
                      wire:click="confirmDeleteTemplate({{ $template->id }})" />
            </div>
          </div>
        @endforeach
      @else
        <p class="rounded-2xl border border-white/10 bg-slate-900/50 p-4 text-sm text-slate-300">
          Aún no hay plantillas guardadas. Mientras tanto se usa {{ $envContentSid ?: 'ningún Content SID del .env' }}.
        </p>
      @endif

      @if ($pendingTemplate)
        <x-modales.confirmacion x-data="{ modalOpen: true }" x-trap.noscroll="modalOpen"
                                 x-on:keydown.escape.window="$wire.cancelDeleteTemplate()"
                                 titulo="Eliminar plantilla">
          <p class="mt-4 text-sm text-slate-300">
            ¿Seguro que quieres eliminar la plantilla
            <span class="font-medium text-white">{{ $pendingTemplate->nombre }}</span>?
          </p>

          <x-slot:actions>
            <x-botones.icono-buton texto="Cancelar" x-on:click="$wire.cancelDeleteTemplate()" />
            <x-botones.icono-buton color="red" icon="papelera" texto="Eliminar"
                                   wire:click="deleteTemplate({{ $pendingTemplate->id }})" />
          </x-slot:actions>
        </x-modales.confirmacion>
      @endif
    </div>
  </div>
</div>
