<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gasto; // ¡Importante importar el modelo!

class GastoController extends Controller
{
    // Función para guardar el gasto
    public function store(Request $request)
    {
        // 1. Validamos que no metan basura
        $request->validate([
            'fecha' => 'required|date',
            'detalle' => 'required|string|max:255',
            'monto' => 'required|numeric|min:0',
        ]);

        // 2. Creamos el gasto
        Gasto::create($request->all());

        // 3. Regresamos a la página anterior con un mensaje
        return back()->with('success', 'Gasto registrado correctamente');
    }

    // Función para borrar (por si acaso)
    public function destroy($id)
    {
        $gasto = Gasto::find($id);
        if ($gasto) {
            $gasto->delete();
        }
        return back()->with('success', 'Gasto eliminado');
    }
}

