@props([
    'icono' => 'abrir',
    'variant' => 'emerald',
])

@php
    $classes = match ($variant) {
        'yellow' => 'border-yellow-400/20 bg-yellow-400/10 text-yellow-300 hover:bg-yellow-400/15',
        'rose' => 'border-rose-400/20 bg-rose-400/10 text-rose-300 hover:bg-rose-400/15',
        default => 'border-emerald-400/20 bg-emerald-400/10 text-emerald-300 hover:bg-emerald-400/15',
    };

    $stroke = match ($variant) {
        'yellow' => 'stroke-yellow-300',
        'rose' => 'stroke-rose-300',
        default => 'stroke-emerald-300',
    };
@endphp

<button
    {{ $attributes->merge([
        'type' => 'button',
        'class' => 'group inline-flex items-center gap-1.5 rounded-full border px-3 py-1 text-xs font-medium uppercase tracking-[0.25em] transition-colors '.$classes,
    ]) }}
>
    <svg viewBox="0 0 14 14" class="size-3.5 {{ $stroke }}" fill="none" aria-hidden="true">
        @if ($icono === 'cerrar')
            <path d="M4 7h6" stroke-linecap="round" stroke-linejoin="round" />
        @elseif ($icono === 'restablecer')
            <path d="M3.5 7a3.5 3.5 0 1 0 1-2.45M3.5 3.5v2.4h2.4" stroke-linecap="round" stroke-linejoin="round" />
        @else
            <path d="M7 3v8M3 7h8" stroke-linecap="round" stroke-linejoin="round" />
        @endif
    </svg>

    {{ $slot }}
</button>
