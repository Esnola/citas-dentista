@props([
    'title',
    'value',
    'icon',
    'detail',
    'badge',
    'color',
    'route' => null,
])

@php
    $colors = [
        'amber' => ['border-amber-500/10 hover:border-amber-500/30 hover:shadow-amber-500/5', 'bg-amber-500/5 group-hover:bg-amber-500/10', 'text-amber-400/80', 'bg-amber-500/10 text-amber-300 group-hover:bg-amber-500/20', 'bg-amber-500/10 text-amber-300'],
        'emerald' => ['border-emerald-500/10 hover:border-emerald-500/30 hover:shadow-emerald-500/5', 'bg-emerald-500/5 group-hover:bg-emerald-500/10', 'text-emerald-400/80', 'bg-emerald-500/10 text-emerald-300 group-hover:bg-emerald-500/20', 'bg-emerald-500/10 text-emerald-300'],
        'red' => ['border-red-500/10 hover:border-red-500/30 hover:shadow-red-500/5', 'bg-red-500/5 group-hover:bg-red-500/10', 'text-red-400/80', 'bg-red-500/10 text-red-300 group-hover:bg-red-500/20', 'bg-red-500/10 text-red-300'],
        'slate' => ['border-slate-500/10 hover:border-slate-500/30 hover:shadow-slate-500/5', 'bg-slate-500/5 group-hover:bg-slate-500/10', 'text-slate-400/80', 'bg-slate-500/10 text-slate-300 group-hover:bg-slate-500/20', 'bg-slate-500/10 text-slate-300'],
        'orange' => ['border-orange-500/10 hover:border-orange-500/30 hover:shadow-orange-500/5', 'bg-orange-500/5 group-hover:bg-orange-500/10', 'text-orange-400/80', 'bg-orange-500/10 text-orange-300 group-hover:bg-orange-500/20', 'bg-orange-500/10 text-orange-300'],
        'indigo' => ['border-indigo-500/10 hover:border-indigo-500/30 hover:shadow-indigo-500/5', 'bg-indigo-500/5 group-hover:bg-indigo-500/10', 'text-indigo-400/80', 'bg-indigo-500/10 text-indigo-300 group-hover:bg-indigo-500/20', 'bg-indigo-500/10 text-indigo-300'],
    ];

    [$cardClasses, $decorationClasses, $titleClasses, $iconClasses, $badgeClasses] = $colors[$color];
@endphp

<div {{ $attributes->class("group relative overflow-hidden rounded-2xl border bg-slate-900/30 p-4 backdrop-blur-xl transition-all duration-300 hover:-translate-y-1 hover:bg-slate-900/60 hover:shadow-xl {$cardClasses}") }}>
    <div class="absolute top-0 right-0 h-16 w-16 rounded-bl-full transition-all duration-300 {{ $decorationClasses }}"></div>
    <div class="flex items-center justify-between">
        <div class="space-y-0.5">
            <p class="text-xs font-semibold tracking-wide uppercase {{ $titleClasses }}">{{ $title }}</p>
            <p class="text-2xl font-extrabold tracking-tight text-white">{{ $value }}</p>
        </div>
        <div class="rounded-xl p-2.5 transition-all duration-300 group-hover:scale-110 {{ $iconClasses }}">
            <x-dynamic-component :component="'iconos.'.$icon" clase="size-5" />
        </div>
    </div>
    <div class="mt-3 flex items-center justify-between border-t border-white/5 pt-2.5">
        <span class="text-[10px] text-slate-400">{{ $detail }}</span>
        <span class="inline-flex items-center rounded-full px-1.5 py-0.5 text-[10px] font-medium {{ $badgeClasses }}">{{ $badge }}</span>
    </div>
</div>
