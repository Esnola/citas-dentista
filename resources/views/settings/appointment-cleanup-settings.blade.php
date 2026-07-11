<div class="rounded-3xl border border-white/10 p-6 shadow-lg md:max-w-[35%]">
  <h3 class="text-lg font-semibold">Mantenimiento de citas</h3>
  <p class="mt-1 text-sm text-slate-300">
    Elige cuánto tiempo deben conservarse las citas pasadas antes de eliminarlas automáticamente junto con sus referencias.
  </p>

  @if ($status)
    <div
      wire:key="appointment-cleanup-status-{{ $statusNonce }}"
      x-data="{
          visible: false,
          timeout: null,
          init() {
              this.$nextTick(() => {
                  this.visible = true;
                  clearTimeout(this.timeout);
                  this.timeout = setTimeout(() => this.visible = false, 5000);
              });
          },
      }"
      x-show="visible"
      x-transition:enter="transition ease-out duration-500"
      x-transition:enter-start="translate-x-full opacity-0"
      x-transition:enter-end="translate-x-0 opacity-100"
      x-transition:leave="transition ease-in duration-500"
      x-transition:leave-start="translate-x-0 opacity-100"
      x-transition:leave-end="translate-x-full opacity-0"
      class="mt-4 flex items-center gap-3 rounded-2xl border border-emerald-400/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
      <x-iconos.check clase="size-5 shrink-0"/>
      <span>{{ $status }}</span>
    </div>
  @endif

  <div class="mt-6 grid gap-5">
    <div class="rounded-2xl border border-white/10 bg-slate-900/50 p-4">
      <label for="retention-period" class="text-sm font-semibold uppercase tracking-[0.22em] text-emerald-200">
        Tiempo de conservación
      </label>
      <p class="mt-1 text-xs text-slate-400">
        Cuando una cita supere este tiempo desde su fecha y hora, se borrará automáticamente.
      </p>

      <div class="mt-4 grid gap-3 sm:grid-cols-2 xl:grid-cols-3">
        @foreach ($retentionOptions as $value => $label)
          <x-formularios.option-input
                  class="text-[10px] text-red-400"
                  name="retention-period"
                  value="{{ $value }}"
                  wire:change="persistRetentionPeriod('{{ $value }}')"
                  :checked="$retentionPeriod === $value"
                  :label="$label" />
        @endforeach
      </div>

      <flux:error name="retentionPeriod"/>
    </div>
  </div>
</div>
