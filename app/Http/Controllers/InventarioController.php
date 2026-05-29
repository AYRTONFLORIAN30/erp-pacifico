<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\IngresoAlmacen;
use Illuminate\Support\Facades\DB;

class InventarioController extends Controller
{
    // 1. Mostrar la pantalla principal del almacén
    public function index()
    {
        $productos = Producto::orderBy('nombre')->get();
        
        $historial = IngresoAlmacen::with('producto')
                        ->orderBy('fecha', 'desc')
                        ->orderBy('created_at', 'desc')
                        ->take(20)
                        ->get();
        
        $totalProductos = Producto::count();
        $alertasStock = Producto::where('stock', '<=', 50)->count();
        $valorInventario = Producto::sum(DB::raw('stock * precio_base'));
        
        return view('inventario.index', compact('productos', 'historial', 'totalProductos', 'alertasStock', 'valorInventario'));
    }

    // 2. Guardar el nuevo stock (¡La Magia Multi-Almacén!)
    public function store(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|numeric|min:0.01',
            'fecha' => 'required|date',
            'motivo' => 'required|string',
            'almacen_destino' => 'required|string|in:Tarma,Lima,Lambayeque' // <-- Validamos el nuevo campo
        ]);

        DB::beginTransaction();

        try {
            // A) Guardamos el historial de por qué y DÓNDE entró este producto
            IngresoAlmacen::create([
                'producto_id' => $request->producto_id,
                'cantidad' => $request->cantidad,
                'motivo' => strtoupper($request->motivo),
                'detalle' => $request->detalle,
                'fecha' => $request->fecha,
                'almacen_destino' => $request->almacen_destino // <-- Guardamos el destino
            ]);

            $producto = Producto::find($request->producto_id);
            
            // B) Le SUMAMOS la cantidad al almacén específico
            if($request->almacen_destino === 'Tarma') {
                $producto->increment('stock_tarma', $request->cantidad);
            } elseif($request->almacen_destino === 'Lima') {
                $producto->increment('stock_lima', $request->cantidad);
            } elseif($request->almacen_destino === 'Lambayeque') {
                $producto->increment('stock_lambayeque', $request->cantidad);
            }

            // C) Sumamos al stock total global (para mantener los reportes generales funcionando)
            $producto->increment('stock', $request->cantidad);

            DB::commit();
            return redirect()->route('inventario.index')->with('success', '¡Stock sumado correctamente al almacén de ' . strtoupper($request->almacen_destino) . '!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al registrar el ingreso: ' . $e->getMessage());
        }
    }

    // 3. Agregar un NUEVO PRODUCTO al catálogo
    public function storeProducto(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:productos,nombre',
            'precio_base' => 'required|numeric|min:0'
        ]);

        Producto::create([
            'nombre' => strtoupper($request->nombre),
            'precio_base' => $request->precio_base,
            'stock' => 0,             //Sr 
            'stock_tarma' => 0,       //Inicializamos los 3 almacenes en cero
            'stock_lima' => 0,        //Stock de productos en inventario en lima
            'stock_lambayeque' => 0   //Stock en el departamento de lambayeque
        ]);

        return redirect()->route('inventario.index')->with('success', '¡Nuevo producto agregado al catálogo con éxito!');
    }
}

