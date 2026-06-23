@extends('layouts.guest')

@section('content')
    <div class="rounded-3xl border border-white/10 bg-white/8 p-8 shadow-2xl shadow-emerald-950/30 backdrop-blur">
        <p class="text-sm uppercase tracking-[0.35em] text-emerald-300">WhatsApp Scheduler</p>
        <h1 class="mt-4 text-4xl font-semibold leading-tight">Programa mensajes de WhatsApp desde Excel o base de datos.</h1>
        <p class="mt-4 text-sm leading-6 text-slate-300">
            Laravel 13, Livewire y Flux para gestionar contactos, programar envíos y automatizar la cola de mensajes.
        </p>
        <div class="mt-8 flex flex-wrap gap-3">
            <x-botones.accion variant="add" icono="check" href="{{ route('login') }}">Entrar</x-botones.accion>
        </div>
    </div>
@endsection
