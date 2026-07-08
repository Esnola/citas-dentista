<div>
  @if (! $enabled)
    <div class="mx-auto rounded-2xl border border-red-400 bg-red-500/30 px-4 py-3 text-sm text-red-200">
      <span class="font-semibold">Aviso:</span> Los envíos automáticos de WhatsApp están deshabilitados. Para reactivarlos, ve a <a href="{{ route('settings.index') }}" class="underline hover:text-red-100">Ajustes</a>.
    </div>
  @endif
</div>
