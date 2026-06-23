@props([
    'abierto',
])

<button
    {{ $attributes->merge([
        'type' => 'button',
        'class' => 'group inline-flex items-center gap-1.5 rounded-full border px-3 py-1 text-xs font-medium uppercase tracking-[0.25em] transition-colors',
    ]) }}
    x-bind:aria-expanded="{{ $abierto }} ? 'true' : 'false'"
    x-bind:class="{{ $abierto }}
        ? 'border-yellow-400/20 bg-yellow-400/10 text-yellow-300 hover:bg-yellow-400/15'
        : 'border-emerald-400/20 bg-emerald-400/10 text-emerald-300 hover:bg-emerald-400/15'"
>
    <svg
        x-show="{{ $abierto }}"
        x-cloak
        viewBox="0 0 14 14"
        class="size-3.5 stroke-yellow-300"
        fill="none"
        aria-hidden="true"
    >
        <path d="M4 4l6 6m0-6l-6 6" stroke-linecap="round" stroke-linejoin="round" />
    </svg>

    <svg
        x-show="! ({{ $abierto }})"
        x-cloak
        viewBox="0 0 14 14"
        class="size-3.5 stroke-emerald-300"
        fill="none"
        aria-hidden="true"
    >
        <path d="M7 3v8M3 7h8" stroke-linecap="round" stroke-linejoin="round" />
    </svg>

    <span x-text="{{ $abierto }} ? 'Contraer' : 'Expandir'"></span>
</button>
