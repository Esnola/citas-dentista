<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function create()
    {
        return view('admin.users.create', [
            'users' => User::query()->orderBy('id')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(4)],
            'is_admin' => ['boolean'],
        ]);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'is_admin' => (bool) ($data['is_admin'] ?? false),
        ]);

        return redirect()->route('admin.users.create')->with('status', 'Usuario creado correctamente.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', [
            'user' => $user,
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {

        $admins = count(User::query()->where('is_admin', true)->get());
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'password' => ['nullable', 'confirmed', Password::min(12)],
            'is_admin' => ['boolean'],
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->is_admin = (bool) ($data['is_admin'] ?? false);

        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        return redirect()->route('admin.users.create')->with('status', 'Usuario actualizado correctamente.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        abort_unless(! $user->is_admin, 422, 'No puedes eliminar a un administrador.');
        abort_unless((int) Auth::id() !== (int) $user->id, 422, 'No puedes eliminar tu propia cuenta.');

        $user->delete();

        return redirect()->route('admin.users.create')->with('status', 'Usuario eliminado correctamente.');
    }
}
