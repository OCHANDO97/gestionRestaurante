<?php

namespace App\Http\Controllers;

use App\Models\RegisterUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rules;
use App\Models\User;
use Inertia\Inertia;

class RegisterUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // return inertia('RegisterUser');
        return Inertia::render('RegisterUser');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
    

    $request->validate([
        'name' => 'required|string|max:255',
        'id_Empleado' => 'required|string|max:255|unique:users,id_Empleado', // Asegura que sea único en la columna `id_Empleado`
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
    ]);

    $user = User::create([
        'name' => $request->name,
        'id_Empleado' => $request->id_Empleado,
        'password' => Hash::make($request->password),
    ]);

    event(new Registered($user));

    return redirect()->route('dashboard')->with('success', 'Usuario registrado correctamente.');
}
    /**
     * Display the specified resource.
     */
    public function show(RegisterUser $registerUser)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RegisterUser $registerUser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RegisterUser $registerUser)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RegisterUser $registerUser)
    {
        //
    }
}
