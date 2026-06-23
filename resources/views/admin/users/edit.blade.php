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
                    <x-formularios.input name="name" value="{{ old('name', $user->name) }}" required autofocus />
                    @error('name') <p class="mt-2 text-sm text-rose-300">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-2 block text-sm text-slate-300">Email</label>
                    <x-formularios.input name="email" type="email" value="{{ old('email', $user->email) }}" required />
                    @error('email') <p class="mt-2 text-sm text-rose-300">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-2 block text-sm text-slate-300">Nueva contraseña</label>
                    <x-formularios.input name="password" type="password" />
                    @error('password') <p class="mt-2 text-sm text-rose-300">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-2 block text-sm text-slate-300">Confirmar nueva contraseña</label>
                    <x-formularios.input name="password_confirmation" type="password" />
                </div>
                <div class="md:col-span-2 flex items-center gap-3">
                    <x-botones.accion variant="add" icono="check" type="submit">Guardar cambios</x-botones.accion>
                    <x-botones.accion href="{{ route('admin.users.create') }}">Volver</x-botones.accion>
                </div>
            </form>
        </div>
    </div>
@endsection
