<div class="grid w-full min-w-0 gap-3">
  @foreach ($alerts as $alert)
    <div @class([
        'relative flex w-full min-w-0 items-start gap-2 overflow-visible rounded-2xl px-4 py-3 text-sm transition-all duration-300',
        'border border-red-400 bg-red-400/10 text-red-200' => $alert['tone'] === 'danger',
        'border border-amber-400/60 bg-amber-500/15 text-amber-100' => $alert['tone'] === 'warning',
    ]) x-data="{ open: false }" x-bind:class="collapsed ? 'justify-center px-2' : 'justify-start'">
      <div
        class=""
        x-on:mouseenter="if (collapsed) open = true"
        x-on:mouseleave="open = false"
        x-on:focusin="if (collapsed) open = true"
        x-on:focusout="open = false"
      >
        <x-iconos.alert/>
      </div>
      <div x-show="!collapsed" x-cloak class="min-w-0 flex-1 sidebar-text">
        <p class="break-words font-semibold">{{ $alert['title'] }}</p>
        <p class="break-words">{{ $alert['message'] }}</p>
      </div>
      <div
        x-show="collapsed && open"
        x-cloak
        x-transition.opacity.duration.150ms
        @class([
            'absolute left-full top-1/2 z-50 ml-3 w-72 max-w-[calc(100vw-7rem)] -translate-y-1/2 rounded-2xl px-4 py-3 text-left text-sm shadow-2xl border bg-slate-950/90 ',
            'border-red-400 text-red-200' => $alert['tone'] === 'danger',
            'border-amber-400/60 text-amber-100' => $alert['tone'] === 'warning',
        ])
      >
        <p class="break-words font-semibold">{{ $alert['title'] }}</p>
        <p @class([
            'mt-1 break-words',
            'text-red-200/90' => $alert['tone'] === 'danger',
            'text-amber-100/90' => $alert['tone'] === 'warning',
        ])>{{ $alert['message'] }}</p>
      </div>
    </div>
  @endforeach
</div>
