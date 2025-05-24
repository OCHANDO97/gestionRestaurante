<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ],[
            'current_password' => 'La contraseña actual es obligatorio.',
            'password.required' => 'La contraseña es obligatoria.'
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back();
        // return redirect()->route('dashboard')->with('success', 'Contraseña actualizada correctamente.');
    }
}
