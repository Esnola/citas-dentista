<div class="grid gap-6">
  @if ($status)
    <div class="rounded-2xl border border-emerald-400/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
      {{ $status }}
    </div>
  @endif

  @if ($templates->isEmpty())
    <div class="rounded-2xl border border-amber-400/30 bg-amber-500/10 px-4 py-3 text-sm text-amber-200">
      No hay ninguna plantilla de Twilio guardada. Crea al menos una para poder usar recordatorios y envíos de nueva cita.
    </div>
  @endif

  <div class="grid gap-6 lg:grid-cols-3">
    <form class="grid content-start gap-5 rounded-2xl border border-white/10 bg-slate-900/50 p-5" wire:submit="addTemplate">
      <div>
        <h4 class="text-lg font-semibold text-white">Nueva plantilla</h4>
        <p class="mt-1 text-sm text-slate-400">Añade una plantilla al catálogo de Twilio.</p>
      </div>

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

      <div class="rounded-2xl border border-white/8 bg-slate-950/35 p-4">
        <flux:radio.group wire:model="variablePreset" label="Variables de la plantilla">
          <div class="grid gap-3 pt-2">
            <flux:radio value="with_name" label='Nombre, día y hora · {"1":"[NOMBRE]","2":"[DIA]","3":"[HORA]"}'/>
            <flux:radio value="appointment" label='Día y hora · {"1":"[DIA]","2":"[HORA]"}'/>
          </div>
        </flux:radio.group>
        <flux:error name="variablePreset"/>
      </div>

      <div class="flex justify-center">
        <x-botones.icono-buton
                icon="disquete"
                type="submit"
                label="Guardar plantilla"
                texto="Guardar plantilla" />
      </div>
    </form>

    <form class="grid content-start gap-5 rounded-2xl border border-white/10 bg-slate-900/50 p-5" wire:submit="saveAssignments">
      <div>
        <h4 class="text-lg font-semibold text-white">Asignaciones</h4>
        <p class="mt-1 text-sm text-slate-400">Elige la plantilla que usará cada flujo.</p>
      </div>

      <div class="rounded-2xl border border-emerald-400/15 bg-emerald-500/[0.05] p-4">
        <flux:field>
          <flux:label>Plantilla para recordatorios de cita</flux:label>
          <x-formularios.select wire:model="appointmentReminderTemplateId">
            @foreach ($templates as $template)
              <option value="{{ $template->id }}">{{ $template->nombre }}</option>
            @endforeach
          </x-formularios.select>
          <flux:error name="appointmentReminderTemplateId"/>
        </flux:field>

        <p class="mt-3 text-[11px] font-semibold uppercase tracking-[0.24em] text-slate-500">Content SID</p>
        <p class="mt-2 font-mono text-sm text-slate-200">{{ $assignedAppointmentReminderTemplate?->content_sid ?? 'Sin plantilla disponible' }}</p>
      </div>

      <div class="rounded-2xl border border-sky-400/15 bg-sky-500/[0.05] p-4">
        <flux:field>
          <flux:label>Plantilla para nueva cita creada</flux:label>
          <x-formularios.select wire:model="appointmentCreatedTemplateId">
            @foreach ($templates as $template)
              <option value="{{ $template->id }}">{{ $template->nombre }}</option>
            @endforeach
          </x-formularios.select>
          <flux:error name="appointmentCreatedTemplateId"/>
        </flux:field>

        <p class="mt-3 text-[11px] font-semibold uppercase tracking-[0.24em] text-slate-500">Content SID</p>
        <p class="mt-2 font-mono text-sm text-slate-200">{{ $assignedAppointmentCreatedTemplate?->content_sid ?? 'Sin plantilla disponible' }}</p>
      </div>

      <div class="flex justify-center">
        <x-botones.icono-buton
                icon="disquete"
                type="submit"
                label="Guardar asignaciones"
                texto="Guardar asignaciones" />
      </div>
    </form>

    <div class="grid content-start gap-4 rounded-2xl border border-white/10 bg-slate-900/50 p-5">
      <div class="flex items-center justify-between gap-3">
        <div>
          <h4 class="text-lg font-semibold text-white">Catálogo</h4>
          <p class="mt-1 text-sm text-slate-400">Plantillas disponibles y uso actual.</p>
        </div>
        <div class="rounded-full border border-white/10 bg-slate-950/40 px-3 py-1.5 text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">
          {{ $templates->count() }}
        </div>
      </div>

      @forelse ($templates as $template)
        <div wire:key="twilio-template-{{ $template->id }}"
             class="rounded-2xl border border-white/10 bg-slate-950/35 p-4">
          <div class="flex items-start justify-between gap-3">
            <div class="min-w-0">
              <p class="text-base font-semibold text-slate-100">{{ $template->nombre }}</p>
              <p class="mt-2 font-mono text-sm text-slate-400">{{ $template->content_sid }}</p>
            </div>

            <x-botones.icono-buton
                    color="red"
                    icon="papelera"
                    label="Eliminar plantilla"
                    texto="Eliminar"
                    wire:click="confirmDeleteTemplate({{ $template->id }})" />
          </div>

          <div class="mt-4 flex flex-wrap gap-2">
            @if ((int) $appointmentReminderTemplateId === $template->id)
              <span class="inline-flex items-center rounded-full border border-emerald-400/30 bg-emerald-500/15 px-3 py-1 text-xs font-semibold text-emerald-300">
                Recordatorios
              </span>
            @endif
            @if ((int) $appointmentCreatedTemplateId === $template->id)
              <span class="inline-flex items-center rounded-full border border-sky-400/30 bg-sky-500/15 px-3 py-1 text-xs font-semibold text-sky-300">
                Nueva cita
              </span>
            @endif
            @if ((int) $appointmentReminderTemplateId !== $template->id && (int) $appointmentCreatedTemplateId !== $template->id)
              <span class="inline-flex items-center rounded-full border border-white/10 bg-slate-950/50 px-3 py-1 text-xs font-semibold text-slate-400">
                Disponible
              </span>
            @endif
          </div>

          <div class="mt-4 rounded-2xl border border-white/8 bg-slate-950/35 p-3">
            <p class="text-[11px] font-semibold uppercase tracking-[0.24em] text-slate-500">Variables</p>
            <p class="mt-2 font-mono text-xs leading-6 text-slate-400">{{ json_encode($template->content_variables, JSON_UNESCAPED_SLASHES) }}</p>
          </div>
        </div>
      @empty
        <p class="rounded-2xl border border-white/10 bg-slate-950/40 p-4 text-sm text-slate-300">
          Aún no hay plantillas guardadas.
        </p>
      @endforelse
    </div>
  </div>

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
