@extends('layouts.app')

@section('content')
    <div class="grid gap-6">
        <div class="rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur">
            <h2 class="text-xl font-semibold">Seguridad del administrador</h2>
            <p class="mt-2 text-sm text-slate-300">
                Usuario activo: #{{ $user->id }} {{ $user->name }}.
            </p>

            <form class="mt-6 grid gap-4 md:grid-cols-2" method="POST" action="{{ route('admin.security.update') }}">
                @csrf
                @method('PUT')
                <div>
                    <label class="mb-2 block text-sm text-slate-300">Nueva contraseña</label>
                    <x-formularios.input name="password" type="password" required autofocus />
                    @error('password') <p class="mt-2 text-sm text-rose-300">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-2 block text-sm text-slate-300">Confirmar nueva contraseña</label>
                    <x-formularios.input name="password_confirmation" type="password" required />
                </div>
                <div class="md:col-span-2">
                  <x-botones.icono-buton
                    type="submit"
                    especial="size-8"
                    label="Guardar la contraseña"
                    texto="Guardar contraseña"
                    icon="guardar"
                    />

                </div>
            </form>
        </div>
    </div>
@endsection
