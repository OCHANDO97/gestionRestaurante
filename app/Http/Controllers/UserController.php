<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        $permissions = Permission::all()->pluck('name'); // Solo los nombres
        // return response()->json($user);
        return Inertia::render('User/index', [
            'users' => $users,
            'allPermissions' => $permissions,
        ]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $User)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $User)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,User $user)
    {
        // $user = User::findOrFail($request->user_id);
        // dd($user); 
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'password' => 'required|confirmed|min:8',
            ], [
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            ]
        );
    
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()->with('success', 'Contraseña actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            $user->delete();
            return redirect()->back()->with('success', 'Usuario eliminado correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'No se pudo eliminar el usuario.');
        }
    }

    // public function updatePermissions(Request $request) {
    //     $request->validate([
    //         'user_id' => 'required|exists:users,id',
    //         'permissions' => 'array',
    //         'permissions.*' => 'string|exists:permissions,name',
    //     ]);

    //     $user = User::find($request->user_id);
    //     $user->syncPermissions($request->permissions);

    //     return response()->json(['message' => 'Permisos actualizados']);
    // }


    public function perfil(User $user) {
            
        return Inertia::render('User/perfil', [
            'user' => $user->load('roles', 'permissions'),
            'allPermissions' => Permission::pluck('name'),
        ]);
    }                                                         

    public function updatePermissions(Request $request, User $user) {
        
        $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        $user->syncPermissions($request->permissions);

        return back()->with('success', 'Permisos actualizados correctamente.');
    }

}