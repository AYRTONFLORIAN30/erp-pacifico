<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role): Response
    {
        // 1. Si no está logueado, mandar al login
        if (! $request->user()) {
            return redirect('/login');
        }

        // 2. Si es el Jefe (admin), déjalo pasar a todo.
        if ($request->user()->rol === 'admin') {
            return $next($request);
        }

        // 3. Si su rol NO es el que pide la ruta, bloquéalo.
        if ($request->user()->rol !== $role) {
            abort(403, 'NO TIENES PERMISO PARA ESTAR AQUÍ.');
        }

        return $next($request);
    }
}

