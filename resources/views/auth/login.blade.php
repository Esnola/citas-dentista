@extends('layouts.guest')

@section('content')
    <div class="rounded-3xl border border-white/10 bg-white/8 p-8 shadow-2xl shadow-slate-950/30 backdrop-blur">
        <h1 class="text-2xl font-semibold">Acceder</h1>
        <form class="mt-6 space-y-4" method="POST" action="{{ route('login') }}">
            @csrf
            <div>
                <label class="mb-2 block text-sm text-slate-300">Email</label>
                <x-formularios.input name="email" type="email" value="{{ old('email') }}" required autofocus />
                @error('email') <p class="mt-2 text-sm text-rose-300">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="mb-2 block text-sm text-slate-300">Contraseña</label>
                <x-formularios.input name="password" type="password" required />
                @error('password') <p class="mt-2 text-sm text-rose-300">{{ $message }}</p> @enderror
            </div>
            <x-formularios.toggle name="remember" texto="Recuérdame" />
            <div class="flex items-center justify-between gap-4">
                <x-botones.accion variant="add" icono="check" type="submit">Entrar</x-botones.accion>
            </div>
        </form>
    </div>
@endsection
