<div class="rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur">

  @if ($status)
    <div @class([
        'mt-4 rounded-2xl border px-4 py-3 text-sm',
        'border-emerald-400/30 bg-emerald-500/10 text-emerald-200' => $statusType === 'success',
        'border-rose-400/30 bg-rose-500/10 text-rose-200' => $statusType === 'error',
        'border-white/10 bg-slate-900/60 text-slate-200' => ! in_array($statusType, ['success', 'error'], true),
    ])>
      {{ $status }}
    </div>
  @endif

  <div class="mt-4 grid gap-4 md:grid-cols-3">

  </div>

  <form class="mt-6 grid " wire:submit="sendTest">
    <div class="grid gap-4 md:grid-cols-6">

      <div class="flex flex-col gap-4">
        <flux:field>
         <flux:label class="mb-1!">Modo</flux:label>
          <x-formularios.select wire:model.live="mode">
            <option value="auto">Auto (.env)</option>
            <option value="sandbox">Sandbox</option>
            <option value="sender">Número real</option>
          </x-formularios.select>
          <flux:error name="mode"/>
      </flux:field>
      <flux:field>
       <flux:label class="mb-1!">Tipo de prueba</flux:label>
        <x-formularios.select wire:model.live="testType">
          <option value="text">Texto</option>
          <option value="template">Plantilla</option>
        </x-formularios.select>
        <flux:error name="testType"/>
      </flux:field>
      </div>
      <div class="flex flex-col justify-between">
        <flux:field>
          <flux:label class="mb-1!">Destino</flux:label>
          <x-formularios.input wire:model.live="recipient" placeholder="+34600123123 o 600123123"/>
          <flux:error name="recipient"/>
        </flux:field>

        <x-botones.icono-buton
                type="submit"
                icon="enviar"
                class="max-w-fit"
                label="Enviar prueba"
                texto="Enviar prueba"
                especial="size-6" />

      </div>

    <div class="col-span-3 max-w-fit">
      @if ($testType !== 'template')
        <flux:field>
         <flux:label class="mb-1!">Mensaje</flux:label>
          <flux:textarea wire:model.live="body" rows="4"/>
          <flux:error name="body"/>
        </flux:field>
      @endif
      @if ($testType === 'template')
        <flux:field>
         <flux:label class="mb-1!">Plantilla</flux:label>
          <x-formularios.select wire:model.live="templateId">
            @forelse ($templates as $template)
              <option value="{{ $template->id }}">
                {{ $template->nombre }} · {{ $template->seleccionada ? ' · En uso' : '' }}
              </option>
            @empty
              <option value="">No hay plantillas guardadas</option>
            @endforelse
          </x-formularios.select>
          <flux:error name="templateId"/>
        </flux:field>
      @endif

      <div class="rounded-2xl border border-white/10 bg-slate-900/50 p-4 text-sm text-slate-300 mt-6">
        <p class="font-medium text-slate-200">Notas de prueba</p>
        <ul class="mt-2 space-y-1">
          <li>• `Plantilla` usa la plantilla elegida aquí y rellena sus variables con datos ficticios para la prueba.</li>
          <li>• La vista previa se actualiza cuando cambias el sender predeterminado o la plantilla seleccionada.</li>
          <li>• Si usas un número local, se normaliza a formato internacional.</li>
        </ul>
      </div>

     </div>
    </div>

    <div class="rounded-2xl border border-white/10 bg-slate-950/50 p-2 mt-6" >
        <div class="flex items-center justify-between gap-3">
          <p class="text-sm font-medium text-slate-200">Vista previa del payload</p>
          <span class="text-xs uppercase tracking-[0.25em] text-slate-500">Antes de enviar</span>
        </div>
      <pre class="mt-3 overflow-x-auto rounded-xl border border-white/10 bg-slate-950/80 p-4 text-xs leading-5 text-slate-200">{{ json_encode($previewPayload['request'] ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
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
