@extends('layouts.guest')

@section('content')
    <div class="rounded-3xl border border-white/10 bg-white/8 p-8 shadow-2xl shadow-slate-950/30 backdrop-blur">
        <h1 class="text-2xl font-semibold">Acceder</h1>
        <form class="mt-6 space-y-4" method="POST" action="{{ route('login') }}">
            @csrf
            <div>
                <label class="mb-2 block text-sm text-slate-300">Email</label>
                <input name="email" type="email" value="{{ old('email') }}" class="w-full rounded-2xl border border-white/10 bg-slate-900/70 px-4 py-3 text-white" required autofocus>
                @error('email') <p class="mt-2 text-sm text-rose-300">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="mb-2 block text-sm text-slate-300">Contraseña</label>
                <input name="password" type="password" class="w-full rounded-2xl border border-white/10 bg-slate-900/70 px-4 py-3 text-white" required>
                @error('password') <p class="mt-2 text-sm text-rose-300">{{ $message }}</p> @enderror
            </div>
            <label class="flex items-center gap-3 text-sm text-slate-300">
                <input type="checkbox" name="remember" class="rounded border-white/20 bg-slate-900/70">
                Recuérdame
            </label>
            <div class="flex items-center justify-between gap-4">
                <flux:button type="submit">Entrar</flux:button>
            </div>
        </form>
    </div>
@endsection
