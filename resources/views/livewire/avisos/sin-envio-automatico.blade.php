<div>
  @if (! $enabled)
    <div class="mx-auto rounded-2xl border border-red-400 bg-red-400/10 px-4 py-3 text-sm text-red-200 flex gap-2">
      <x-iconos.alert/>
      <span class="font-semibold"> Envíos automáticos deshabilitados.</span>
    </div>
  @endif
</div>
