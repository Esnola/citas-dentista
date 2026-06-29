@extends('layouts.app')

@section('content')
    <div class="grid gap-6 max-w-4xl mx-auto">
        <div class="rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur">
            <h2 class="text-xl font-semibold">Crear usuario</h2>
            <form class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4" method="POST" action="{{ route('admin.users.store') }}">
                @csrf
                <div>
                    <label class="mb-2 block text-sm text-slate-300">Nombre</label>
                    <x-formularios.input name="name" value="{{ old('name') }}" required autofocus />
                    @error('name') <p class="mt-2 text-sm text-rose-300">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-2 block text-sm text-slate-300">Email</label>
                    <x-formularios.input name="email" type="email" value="{{ old('email') }}" required />
                    @error('email') <p class="mt-2 text-sm text-rose-300">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-2 block text-sm text-slate-300">Contraseña</label>
                    <x-formularios.input name="password" type="password" required />
                    @error('password') <p class="mt-2 text-sm text-rose-300">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-2 block text-sm text-slate-300">Confirmar contraseña</label>
                    <x-formularios.input name="password_confirmation" type="password" required />
                </div>
                <div class="flex flex-col gap-8">

                  <flux:checkbox name="is_admin" label="Administrador" :checked="old('is_admin')" />
                  <x-botones.icono-buton
                    color="emerald"
                    type="submit"
                    icon="usuario-plus"
                    label="Crear usuario"
                    texto="Crear usuario"
                    class="max-w-fit"
                  />


                </div>
            </form>
        </div>

        <div class="rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur">
            <h2 class="text-xl font-semibold">Usuarios existentes</h2>
            <div class="mt-4 overflow-hidden rounded-2xl border border-white/10">
                <table class="min-w-full divide-y divide-white/10 text-left text-sm">
                    <thead class="bg-slate-900/70 text-slate-300">
                        <tr>
                            <th class="px-4 py-3">ID</th>
                            <th class="px-4 py-3">Nombre</th>
                            <th class="px-4 py-3">Email</th>
                            <th class="px-4 py-3">Rol</th>
                            <th class="px-4 py-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10 bg-slate-950/40">
                        @foreach ($users as $user)
                            <tr>
                                <td class="px-4 py-3">{{ $user->id }}</td>
                                <td class="px-4 py-3">{{ $user->name }}</td>
                                <td class="px-4 py-3">{{ $user->email }}</td>
                                <td class="px-4 py-3">
                                    @if ($user->is_admin)
                                        <span class="inline-flex items-center rounded-full bg-amber-400/15 px-2.5 py-0.5 text-xs font-semibold text-amber-200 ring-1 ring-inset ring-amber-400/30">Admin</span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-slate-400/15 px-2.5 py-0.5 text-xs font-semibold text-slate-300 ring-1 ring-inset ring-slate-400/30">Usuario</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                      <x-botones.icono-buton
                                              color="blue"
                                              icon="lapiz"
                                              label="Edita usuario"
                                              onclick="window.location.href='{{ route('admin.users.edit', $user) }}'"
                                      />

                                        @if (! $user->is_admin || $user->id === Auth::id())
                                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('¿Eliminar este usuario?')">
                                                @csrf
                                                @method('DELETE')
                                              <x-botones.icono-buton
                                                      color="red"
                                                      type="submit"
                                                      icon="user-menos"
                                                      label="Eliminar usuario"
                                              />
                                            </form>
                                        @else
                                            <span class="text-slate-500">Protegido</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
