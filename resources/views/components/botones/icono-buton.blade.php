@props([
    'color' => 'emerald',
    'icon',
    'label',
    'texto'=>null,
    'type'=>'button',
    'especial'=> 'size-4',
])

@php
  $colors = [
      'emerald' => 'border-emerald-400/25 bg-emerald-400/10 text-emerald-200 hover:bg-emerald-400/15 hover:text-emerald-100',
      'blue'    => 'border-blue-400/25 bg-blue-400/10 text-blue-200 hover:bg-blue-400/15 hover:text-blue-100',
      'red'     => 'border-red-400/25 bg-red-400/10 text-red-200 hover:bg-red-400/15 hover:text-red-100',
      'amber'   => 'border-amber-400/25 bg-amber-400/10 text-amber-200 hover:bg-amber-400/15 hover:text-amber-100',
      'indigo'   => 'border-indigo-400/25 bg-indigo-400/10 text-indigo-200 hover:bg-indigo-400/15 hover:text-indigo-100',
      'gray'    => 'border-white/15 bg-white/5 text-white/60 hover:bg-white/10 hover:text-white',
  ];
@endphp

<button
        {{ $attributes->class([
            'group inline-flex items-center gap-4 justify-center rounded-full border p-2 transition-colors duration-100',
            'disabled:pointer-events-none disabled:opacity-50',
            'aria-disabled:pointer-events-none aria-disabled:opacity-50',
            'text-sm font-medium px-4! py-2!' => $texto,
            $colors[$color] ?? $colors['gray'],
        ]) }}
        type="{{ $type }}"
        aria-label="{{ $label }}"
        title="{{ $label }}"
>
  @if($texto)
    <x-dynamic-component :component="'iconos.'.$icon" :clase="$especial"/>
    {{ $texto }}
  @else
    <x-dynamic-component :component="'iconos.'.$icon"/>
  @endif
</button>
