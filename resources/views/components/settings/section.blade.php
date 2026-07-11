@props([
    'id',
    'title',
    'description',
    'defaultOpen' => true,
    'dragLabel' => 'Sección en movimiento',
])

<section data-settings-section="{{ $id }}"
         data-default-open="{{ $defaultOpen ? 'true' : 'false' }}"
         {{ $attributes->merge([
             'class' => 'rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur',
         ]) }}
         x-bind:class="sectionStateClasses(@js($id))"
         x-on:dragenter.prevent="setDropTarget(@js($id), $event)"
         x-on:dragover.prevent
         x-on:drop.prevent="drop(@js($id), $event)">
  <div x-show="showDropHint(@js($id), 'before')" x-cloak
       class="mb-4 h-1 rounded-full bg-emerald-400/80 shadow-[0_0_24px_rgba(52,211,153,0.45)]"></div>

  <div class="flex cursor-pointer items-center justify-between gap-4"
       x-on:click="toggle(@js($id))">
    <div class="flex items-center gap-3">
      <x-botones.arrastrar-seccion :seccion="$id"/>
      <div>
        <h3 class="text-lg font-semibold">{{ $title }}</h3>
        <p class="text-sm text-slate-300">{{ $description }}</p>
      </div>
    </div>

    <x-botones.expandir-contraer abierto="isOpen('{{ $id }}')" :seccion="$id"/>
  </div>

  <div x-show="dragging === @js($id)" x-cloak
       class="mt-4 rounded-2xl border border-emerald-400/20 bg-emerald-500/10 px-4 py-3 text-xs uppercase tracking-[0.28em] text-emerald-100">
    {{ $dragLabel }}
  </div>

  <div x-show="isOpen(@js($id))" x-cloak class="mt-6">
    {{ $slot }}
  </div>

  <div x-show="showDropHint(@js($id), 'after')" x-cloak
       class="mt-4 h-1 rounded-full bg-emerald-400/80 shadow-[0_0_24px_rgba(52,211,153,0.45)]"></div>
</section>
