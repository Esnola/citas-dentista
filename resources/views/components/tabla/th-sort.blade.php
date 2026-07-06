@props([
    'sortBy',
    'sortDirection',
    'currentSort',
])

@php
  $isActive = $currentSort === $sortBy;
  $rumbo = $isActive ? $sortDirection : 'asc';

  $iconos = [
      'fecha' => ['asc' => 'iconos.num-Asc', 'desc' => 'iconos.num-Desc'],
  ];

  $icono = $iconos[$sortBy][$rumbo];
@endphp

<th class="px-4 py-3 text-center">
  <button type="button"
          class="inline-flex cursor-pointer items-center gap-1 font-semibold text-slate-200 hover:text-white"
          wire:click="toggleSortDirection"
          title="Cambiar orden"
          aria-label="Cambiar orden">
    {{ ucfirst($sortBy) }}
    <span class="text-xs text-slate-400">
            <x-dynamic-component :component="$icono" />
        </span>
  </button>
</th>
