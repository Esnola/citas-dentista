@extends('layouts.app')

@section('content')
    <div class="grid gap-6">
        @if (session('status'))
            <div class="rounded-2xl border border-emerald-400/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
                {{ session('status') }}
            </div>
        @endif

        <div class="rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur">
            <h2 class="text-xl font-semibold">Crear usuario</h2>
            <form class="mt-6 grid gap-4 md:grid-cols-2" method="POST" action="{{ route('admin.users.store') }}">
                @csrf
                <div>
                    <label class="mb-2 block text-sm text-slate-300">Nombre</label>
                    <input name="name" type="text" value="{{ old('name') }}" class="w-full rounded-2xl border border-white/10 bg-slate-900/70 px-4 py-3 text-white" required autofocus>
                    @error('name') <p class="mt-2 text-sm text-rose-300">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-2 block text-sm text-slate-300">Email</label>
                    <input name="email" type="email" value="{{ old('email') }}" class="w-full rounded-2xl border border-white/10 bg-slate-900/70 px-4 py-3 text-white" required>
                    @error('email') <p class="mt-2 text-sm text-rose-300">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-2 block text-sm text-slate-300">Contraseña</label>
                    <input name="password" type="password" class="w-full rounded-2xl border border-white/10 bg-slate-900/70 px-4 py-3 text-white" required>
                    @error('password') <p class="mt-2 text-sm text-rose-300">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-2 block text-sm text-slate-300">Confirmar contraseña</label>
                    <input name="password_confirmation" type="password" class="w-full rounded-2xl border border-white/10 bg-slate-900/70 px-4 py-3 text-white" required>
                </div>
                <div class="md:col-span-2">
                    <flux:button type="submit">Crear usuario</flux:button>
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
                                    <div class="flex items-center gap-3">
                                        <a class="text-emerald-300 hover:text-emerald-200" href="{{ route('admin.users.edit', $user) }}">Editar</a>
                                        @if ($user->id !== 1)
                                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('¿Eliminar este usuario?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="text-rose-300 hover:text-rose-200" type="submit">Eliminar</button>
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
