<div wire:poll.{{ $pollInterval }}s="pollUpdates">
  @if ($items->isNotEmpty())
    <div
            x-data="{
            x: 16,
            y: 16,
            dragging: false,
            offsetX: 0,
            offsetY: 0,
            init() {
                const savedX = parseInt(localStorage.getItem('unread-responses-notice:x') || '', 10);
                const savedY = parseInt(localStorage.getItem('unread-responses-notice:y') || '', 10);
                this.x = Number.isFinite(savedX) ? savedX : 16;
                this.y = Number.isFinite(savedY) ? savedY : 16;
                this.clampToViewport();
                window.addEventListener('resize', () => this.clampToViewport());
            },
            startDrag(event) {
                this.dragging = true;
                this.offsetX = event.clientX - this.x;
                this.offsetY = event.clientY - this.y;
            },
            drag(event) {
                if (!this.dragging) return;
                this.x = event.clientX - this.offsetX;
                this.y = event.clientY - this.offsetY;
                this.clampToViewport();
            },
            stopDrag() {
                if (!this.dragging) return;
                this.dragging = false;
                localStorage.setItem('unread-responses-notice:x', String(this.x));
                localStorage.setItem('unread-responses-notice:y', String(this.y));
            },
            clampToViewport() {
                const panel = this.$refs.panel;
                if (!panel) return;
                const maxX = Math.max(8, window.innerWidth - panel.offsetWidth - 8);
                const maxY = Math.max(8, window.innerHeight - panel.offsetHeight - 8);
                this.x = Math.min(Math.max(8, this.x), maxX);
                this.y = Math.min(Math.max(8, this.y), maxY);
            },
        }"
            x-on:pointermove.window="drag($event)"
            x-on:pointerup.window="stopDrag()"
            x-on:pointercancel.window="stopDrag()"
            x-on:mouseleave.window="stopDrag()"
            x-bind:style="`left:${x}px; top:${y}px;`"
            x-ref="panel"
            class="fixed z-100 w-88 max-w-[calc(100vw-2rem)]"
    >
      <section
              class="overflow-hidden rounded-3xl border-2 border-orange-300/70 bg-slate-950/95 shadow-[0_0_0_1px_rgba(253,186,116,0.22),0_18px_60px_rgba(249,115,22,0.18)] backdrop-blur-xl">
        <div
                x-on:pointerdown.prevent="startDrag($event)"
                class="flex cursor-grab items-center justify-between gap-3 border-b border-orange-200/15 bg-linear-to-r from-orange-400/18 via-amber-300/10 to-transparent px-4 py-3 active:cursor-grabbing"
        >
          <div class="flex min-w-0 items-center gap-3">
            <span class="flex size-10 shrink-0 items-center justify-center rounded-2xl bg-orange-300/18 text-orange-100 shadow-lg shadow-orange-950/30 ring-1 ring-inset ring-orange-200/35">
              <x-iconos.alert clase="size-6 animate-pulse"/>
            </span>
            <div class="min-w-0">
              <p class="text-sm font-semibold tracking-wide text-orange-50">Nuevas respuestas de clientes</p>
            </div>
          </div>
          <span class="flex shrink-0 items-center gap-1 rounded-full border border-orange-200/20 bg-slate-900/60 px-2 py-1 text-[10px] font-semibold uppercase tracking-[0.2em] text-orange-100">
            <x-iconos.arrastrar clase="size-3.5"/>
            Mover
          </span>
        </div>
        <div class="max-h-[55vh] overflow-y-auto px-3 py-3">
          <div class="space-y-2">
            @foreach ($items as $item)
              <div class="flex items-start justify-between gap-3">
                <div class="min-w-0">
                  <p class="truncate text-sm font-semibold text-orange-50">{{ $item['client_name'] }}</p>
                  @if ($item['response_badge'])
                    <span class="mt-1 inline-flex max-w-full items-center gap-1 rounded-full px-2 py-0.5 text-[10px] font-semibold {{ $item['response_badge']['classes'] }}">
                      <span class="truncate">{{ $item['response_badge']['label'] }}</span>
                    </span>
                  @endif
                </div>
                <div class="flex shrink-0 items-center gap-1.5">
                  <a wire:key="unread-response-history-{{ $item['appointment_id'] }}"
                     href="{{ $item['url'] }}"
                     class="rounded-full bg-sky-300/12 px-3 py-1 text-[10px] font-semibold text-sky-100 ring-1 ring-inset ring-sky-300/30 hover:bg-sky-200/20 hover:scale-105 transition">
                    Chat
                  </a>
                  <a wire:key="unread-response-appointments-{{ $item['appointment_id'] }}"
                     href="{{ $item['client_url'] }}"
                     class="rounded-full bg-orange-300/12 px-3 py-1 text-[10px] font-semibold text-orange-100 ring-1 ring-inset ring-orange-300/30 hover:bg-orange-200/20 hover:scale-105 transition">
                    Citas
                  </a>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </section>
    </div>
  @endif
</div>
