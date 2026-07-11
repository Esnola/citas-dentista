@props([
    'label',
    'description' => null
])

<label @class([
    'group relative flex cursor-pointer items-start gap-3 rounded-2xl border px-4 py-3 transition-all duration-200',
    'border-white/10 bg-slate-950/45 hover:border-emerald-400/30 hover:bg-emerald-500/10 hover:shadow-[0_12px_40px_rgba(16,185,129,0.12)]',
])>
  <input {{ $attributes->class([
      'peer sr-only',
  ])->merge([
      'type' => 'radio',
  ]) }}>

  <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full border border-white/15 bg-slate-900/80 transition-all duration-200 peer-checked:border-emerald-300/60 peer-checked:bg-emerald-400/20 peer-focus-visible:ring-2 peer-focus-visible:ring-emerald-300/40">
    <span class="h-2.5 w-2.5 rounded-full bg-emerald-200 opacity-0 transition-all duration-150 peer-checked:opacity-100"></span>
  </span>

  <span class="min-w-0">
    <span class="block text-sm font-medium text-slate-100">{{ $label }}</span>
    @if ($description)
      <span class="mt-0.5 block text-xs text-slate-400">{{ $description }}</span>
    @endif
  </span>
</label>
