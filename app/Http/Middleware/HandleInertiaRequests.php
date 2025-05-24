<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = $request->user();
        
        return [
            
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
                'canViewFacturas' => $user ? $user->can('ver_facturas') : false,
                'canCreateAccount' => $user ? $user->can('crear_cuentas') : false,
                'canUser' => $user ? $user->can('ver_usuarios') : false,
                'canImportar' => $user ? $user->can('importar_csv') : false,
                'canComanda' => $user ? $user->can('crear_comanda') : false,
                'canPerfil' => $user ? $user->can('ver_perfil') : false,
                

            ],
        ];
    }
}
