<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // OBTENER EL ROL DEL USUARIO
        $rol = $request->user()->rol;

        // 1. CARLOS (Dueño/Admin) -> Va al Dashboard General
        if ($rol === 'admin') {
            return redirect()->route('dashboard');
        }

        // 2. LUZBITH (Egresos) -> Va directo a la Caja
        if ($rol === 'egresos') {
            return redirect()->route('egresos.index');
        }

        // 3. SARA (Ventas) -> ✅ ¡ACTIVADO!
        // Como ya creamos el controlador y las vistas, ahora sí la dejamos pasar.
        if ($rol === 'ventas') {
             return redirect()->route('ventas.index'); 
        }

        // 4. OTROS (Almendra/Ruth - Saldos/Planilla) -> Siguen en Construcción
        // (Asegúrate de que en web.php la ruta se llame 'en_construccion')
        return redirect()->route('en_construccion');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}

