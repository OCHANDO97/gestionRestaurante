<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // public function handle(Request $request, Closure $next, string $role): Response
    // {
    //     if (!Auth::check() || !Auth::user()->hasRole($role)) {
    //         abort(403, 'No tienes permiso para acceder.');
    //     }

    //     return $next($request);
        
    // }

    public function handle(Request $request, Closure $next,string $role): Response
    {
        
        if (!Auth::user()->can($role)) {
            abort(403, 'No tienes permiso para acceder.');
        }

        // Verifica si el usuario tiene el rol necesario
        // if (!Auth::user()->hasRole('Administrador')) {
            // if (!Auth::user()->hasRole($role)) {
            // // abort(403, 'No tienes permiso para acceder.');
            //     return $next($request);
            // }

        return $next($request);
        
    }
}
