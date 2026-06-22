@extends('layouts.app')

@section('content')
    <div class="grid gap-6">
        <div class="rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur">
            <h2 class="text-xl font-semibold">Editar usuario #{{ $user->id }}</h2>
            <form class="mt-6 grid gap-4 md:grid-cols-2" method="POST" action="{{ route('admin.users.update', $user) }}">
                @csrf
                @method('PUT')
                <div>
                    <label class="mb-2 block text-sm text-slate-300">Nombre</label>
                    <input name="name" type="text" value="{{ old('name', $user->name) }}" class="w-full rounded-2xl border border-white/10 bg-slate-900/70 px-4 py-3 text-white" required autofocus>
                    @error('name') <p class="mt-2 text-sm text-rose-300">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-2 block text-sm text-slate-300">Email</label>
                    <input name="email" type="email" value="{{ old('email', $user->email) }}" class="w-full rounded-2xl border border-white/10 bg-slate-900/70 px-4 py-3 text-white" required>
                    @error('email') <p class="mt-2 text-sm text-rose-300">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-2 block text-sm text-slate-300">Nueva contraseña</label>
                    <input name="password" type="password" class="w-full rounded-2xl border border-white/10 bg-slate-900/70 px-4 py-3 text-white">
                    @error('password') <p class="mt-2 text-sm text-rose-300">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-2 block text-sm text-slate-300">Confirmar nueva contraseña</label>
                    <input name="password_confirmation" type="password" class="w-full rounded-2xl border border-white/10 bg-slate-900/70 px-4 py-3 text-white">
                </div>
                <div class="md:col-span-2 flex items-center gap-3">
                    <flux:button type="submit">Guardar cambios</flux:button>
                    <a class="text-sm text-slate-300 hover:text-white" href="{{ route('admin.users.create') }}">Volver</a>
                </div>
            </form>
        </div>
    </div>
@endsection
