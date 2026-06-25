@props([
    'variant' => 'warning',
    'size' => 'md',
    'icono' => null,
    'tooltip' => null,
])

@php
    $variantClasses = match ($variant) {
        'add' => 'border-emerald-400/25 bg-emerald-400/10 text-emerald-200 hover:bg-emerald-400/15 hover:text-emerald-100',
        'edit' => 'border-sky-400/25 bg-sky-400/10 text-sky-200 hover:bg-sky-400/15 hover:text-sky-100',
        'delete' => 'border-rose-400/25 bg-rose-400/10 text-rose-200 hover:bg-rose-400/15 hover:text-rose-100',
        'warning' => 'border-yellow-400/25 bg-yellow-400/10 text-yellow-200 hover:bg-yellow-400/15 hover:text-yellow-100',
        'indigo' => 'border-indigo-400/25 bg-indigo-400/10 text-indigo-200 hover:bg-indigo-400/15 hover:text-indigo-100',
        default => 'border-white/10 bg-white/5 text-slate-200 hover:border-white/20 hover:bg-white/10 hover:text-white',
    };

    $sizeClasses = match ($size) {
        'xs' => 'px-2.5 py-1 text-xs',
        'sm' => 'px-3 py-1.5 text-xs',
        'icon' => 'size-8 p-0',
        default => 'px-4 py-2 text-sm',
    };

    $iconClasses = match ($variant) {
        'add' => 'stroke-emerald-300',
        'edit' => 'stroke-sky-300',
        'delete' => 'stroke-rose-300',
        'warning' => 'stroke-yellow-300',
        'indigo' => 'stroke-indigo-300',
        default => 'stroke-slate-300',
    };

    $baseClasses = 'group inline-flex items-center justify-center gap-2 rounded-full border font-medium transition-colors disabled:pointer-events-none disabled:opacity-50 aria-disabled:pointer-events-none aria-disabled:opacity-50';
    $tooltipAttributes = filled($tooltip)
        ? ['title' => $tooltip, 'aria-label' => $tooltip]
        : [];
@endphp

@if ($attributes->has('href'))
    <a {{ $attributes->class([$baseClasses, $sizeClasses, $variantClasses])->merge($tooltipAttributes) }}>
        @if ($icono)
            <svg viewBox="0 0 14 14" class="size-6 {{ $iconClasses }}" fill="none" aria-hidden="true">
                @include('components.botones.partials.icono-accion', ['icono' => $icono])
            </svg>
        @endif
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->class([$baseClasses, $sizeClasses, $variantClasses])->merge(['type' => 'button'] + $tooltipAttributes) }}>
        @if ($icono)
            <svg viewBox="0 0 14 14" class="size-3.5 {{ $iconClasses }}" fill="none" aria-hidden="true">
                @include('components.botones.partials.icono-accion', ['icono' => $icono])
            </svg>
        @endif
        {{ $slot }}
    </button>
@endif
