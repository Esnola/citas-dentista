@props([
    'sortBy',
    'sortDirection',
    'currentSort',
])

<th class="px-4 py-3">
  <button type="button"
          class="inline-flex cursor-pointer items-center gap-1 font-semibold text-slate-200 hover:text-white"
          wire:click="sortByColumn('{{ $sortBy }}')"
          title="Ordenar por {{ $sortBy }}"
          aria-label="Ordenar por {{ $sortBy }}">
    {{ ucfirst($sortBy) }}
    <span class="text-xs text-slate-400">
    @if ($currentSort === $sortBy && $sortDirection === 'asc')
        @if ($sortBy === 'cliente')
          <x-iconos.deAZ />
        @else
          <x-iconos.num-Asc />
        @endif
      @elseif ($currentSort === $sortBy)
        @if ($sortBy === 'cliente')
          <x-iconos.deZA />
        @else
          <x-iconos.num-Desc />
        @endif
      @else
        @if ($sortBy === 'cliente')
          <x-iconos.deAZ />
        @else
          <x-iconos.num-Asc />
        @endif
      @endif
</span>
  </button>
</th>
