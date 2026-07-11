@props([
    'label',
    'description' => null,
    'disabled' => false,
])

@php
    $isDisabled = filter_var($disabled, FILTER_VALIDATE_BOOL);
@endphp

<label @class([
    'group relative flex max-w-fit items-center gap-3 rounded-2xl border px-4 py-3 transition-all duration-200',
    'cursor-pointer border-white/10 bg-slate-950/45 hover:border-emerald-400/30 hover:bg-emerald-500/10 hover:shadow-[0_12px_40px_rgba(16,185,129,0.12)]' => ! $isDisabled,
    'cursor-not-allowed border-white/5 bg-slate-950/20 opacity-50' => $isDisabled,
])>
  <input {{ $attributes->class([
      'peer sr-only',
  ])->merge([
      'type' => 'checkbox',
      'disabled' => $isDisabled,
  ]) }}>

  <span @class([
      'flex h-5 w-5 shrink-0 items-center justify-center rounded-md border transition-all duration-200',
      'border-white/15 bg-slate-900/80 peer-checked:border-emerald-300/60 peer-checked:bg-emerald-400/20 peer-focus-visible:ring-2 peer-focus-visible:ring-emerald-300/40 peer-checked:[&>svg]:scale-100 peer-checked:[&>svg]:opacity-100' => ! $isDisabled,
      'border-white/10 bg-slate-900/40' => $isDisabled,
  ])>
    <svg class="h-3.5 w-3.5 scale-75 text-emerald-200 opacity-0 transition-all duration-150"
         viewBox="0 0 20 20"
         fill="currentColor"
         aria-hidden="true">
      <path fill-rule="evenodd"
            d="M16.704 5.29a1 1 0 0 1 .006 1.414l-7.25 7.312a1 1 0 0 1-1.42-.003l-3.75-3.812a1 1 0 1 1 1.424-1.405l3.04 3.088 6.54-6.594a1 1 0 0 1 1.41 0Z"
            clip-rule="evenodd"/>
    </svg>
  </span>

  <span class="min-w-0">
    <span class="block text-sm font-medium text-slate-100">{{ $label }}</span>
    @if ($description)
      <span class="mt-0.5 block text-xs text-slate-400">{{ $description }}</span>
    @endif
  </span>
</label>
