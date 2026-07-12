<div class="grid gap-6 lg:grid-cols-2">

  {{-- Export --}}
  <div class="rounded-2xl border border-white/10 bg-slate-900/50 p-5">
    <div class="flex items-center gap-3">
      <x-iconos.enviar clase="size-5 shrink-0 text-emerald-300"/>
      <div>
        <h3 class="text-sm font-semibold uppercase tracking-[0.22em] text-emerald-200">Exportar</h3>
        <p class="mt-0.5 text-xs text-slate-400">Descarga la base de datos completa.</p>
      </div>
    </div>
    <div class="mt-5 flex flex-wrap gap-3">
      <a href="{{ route('admin.export.all-json') }}"
         class="inline-flex items-center gap-1.5 rounded-full border border-emerald-400/20 bg-emerald-400/10 px-4 py-1.5 text-xs font-medium uppercase tracking-[0.25em] text-emerald-300 hover:bg-emerald-400/15 transition-colors">
        <x-iconos.enviar clase="size-3.5"/>
        JSON
      </a>
      <a href="{{ route('admin.export.all-csv') }}"
         class="inline-flex items-center gap-1.5 rounded-full border border-yellow-400/20 bg-yellow-400/10 px-4 py-1.5 text-xs font-medium uppercase tracking-[0.25em] text-yellow-300 hover:bg-yellow-400/15 transition-colors">
        <x-iconos.excel clase="size-3.5"/>
        CSV (ZIP)
      </a>
    </div>
  </div>

  {{-- Import --}}
  <div class="rounded-2xl border border-white/10 bg-slate-900/50 p-5">
    <div class="flex items-center gap-3">
      <x-iconos.excel clase="size-5 shrink-0 text-yellow-300"/>
      <div>
        <h3 class="text-sm font-semibold uppercase tracking-[0.22em] text-yellow-200">Importar</h3>
        <p class="mt-0.5 text-xs text-slate-400">Restaura desde JSON o ZIP con CSVs.</p>
      </div>
    </div>

    <div class="mt-5">
      <label class="block">
        <span class="sr-only">Seleccionar archivo de backup</span>
        <input type="file"
               accept=".json,.zip"
               wire:model.live="importFile"
               class="block w-full text-sm text-slate-400 file:mr-3 file:rounded-full file:border-0 file:px-3 file:py-1 file:text-xs file:font-medium file:uppercase file:tracking-[0.2em] file:transition-colors file:cursor-pointer
                      file:border file:border-white/10 file:bg-white/5 file:text-slate-300
                      hover:file:bg-white/10 hover:file:text-white
                      focus:file:outline-none focus:file:ring-2 focus:file:ring-yellow-400/50">
      </label>
    </div>

    <div class="mt-4 flex items-center gap-3">
      <button wire:click="importDatabase"
              wire:loading.attr="disabled"
              @if(! $importFile) disabled @endif
              class="inline-flex items-center gap-1.5 rounded-full border px-4 py-1.5 text-xs font-medium uppercase tracking-[0.25em] transition-colors disabled:opacity-50
                     @if($confirmImport)
                       border-rose-400/20 bg-rose-400/10 text-rose-300 hover:bg-rose-400/15
                     @else
                       border-yellow-400/20 bg-yellow-400/10 text-yellow-300 hover:bg-yellow-400/15
                     @endif">
        <x-iconos.excel clase="size-3.5"/>
        <span wire:loading.remove wire:target="importDatabase">
          @if($confirmImport) Confirmar @else Importar @endif
        </span>
        <span wire:loading wire:target="importDatabase">Importando...</span>
      </button>

      @if ($importStatus)
        <div
          wire:key="import-status-{{ $importStatusNonce }}"
          x-data="{ visible: false, init() { this.$nextTick(() => { this.visible = true; setTimeout(() => this.visible = false, 4000); }); } }"
          x-show="visible"
          x-transition:enter="transition ease-out duration-300"
          x-transition:enter-start="opacity-0 -translate-y-1"
          x-transition:enter-end="opacity-100 translate-y-0"
          x-transition:leave="transition ease-in duration-200"
          x-transition:leave-start="opacity-100"
          x-transition:leave-end="opacity-0"
          class="flex items-center gap-2 rounded-xl border px-3 py-1.5 text-xs
                 @if($confirmImport && !str_contains($importStatus, 'correctamente'))
                   border-yellow-400/30 bg-yellow-500/10 text-yellow-200
                 @else
                   border-emerald-400/30 bg-emerald-500/10 text-emerald-200
                 @endif">
          <x-iconos.check clase="size-3.5 shrink-0"/>
          {{ $importStatus }}
        </div>
      @endif
    </div>
  </div>

</div>
