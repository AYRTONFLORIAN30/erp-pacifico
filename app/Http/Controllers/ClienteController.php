<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\ClientesImport;
use App\Models\Cliente;
use Maatwebsite\Excel\Facades\Excel;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::orderBy('razon_social')->get();
        return view('clientes.index', compact('clientes'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'archivo_excel' => 'required|mimes:xlsx,xls'
        ]);

        try {
            Excel::import(new ClientesImport, $request->file('archivo_excel'));
            return redirect()->route('clientes.index')->with('success', '¡Base de datos de clientes actualizada con éxito!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al importar: ' . $e->getMessage());
        }
    }

    // --- NUEVA FUNCIÓN PARA EL AUTOCOMPLETADO EN VENTAS ---
    public function buscarPorRuc($ruc)
    {
        // Buscamos al cliente en la base de datos por su RUC
        $cliente = Cliente::where('ruc', $ruc)->first();

        // Si lo encuentra, devuelve un JSON con sus datos para que Alpine.js los lea
        if ($cliente) {
            return response()->json([
                'encontrado'   => true,
                'razon_social' => $cliente->razon_social,
                'lugar'        => $cliente->lugar,
                'direccion'    => $cliente->direccion,
            ]);
        }

        // Si no lo encuentra, avisa que es un cliente nuevo
        return response()->json([
            'encontrado' => false
        ]);
    }
}

