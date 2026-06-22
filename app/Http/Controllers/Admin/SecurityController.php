<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class SecurityController extends Controller
{
    public function edit(Request $request)
    {
        return view('admin.security.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'password' => ['required', 'confirmed', Password::min(12)],
        ]);

        $request->user()->update([
            'password' => Hash::make($data['password']),
        ]);

        return redirect()->route('admin.security.edit')->with('status', 'Contraseña actualizada correctamente.');
    }
}
