<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\VentaDetalle;
use App\Models\Producto;
use App\Models\Gasto;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VentaController extends Controller
{
    public function index(Request $request)
    {
        $rol = Auth::user()->rol;
        if ($rol !== 'admin' && $rol !== 'ventas') abort(403);

        $defaultInicio = now()->startOfMonth()->format('Y-m-d');
        $defaultFin = now()->format('Y-m-d');

        $fecha_inicio = $request->get('fecha_inicio', $defaultInicio);
        $fecha_fin = $request->get('fecha_fin', $defaultFin);
        $movimiento = $request->get('movimiento', 'todos');
        $vendedor = $request->get('vendedor', 'todos');
        $producto_filtro = $request->get('producto', 'todos');

        $fecha_inicio_fuera = $request->get('fecha_inicio_fuera', $defaultInicio);
        $fecha_fin_fuera = $request->get('fecha_fin_fuera', $defaultFin);

        // Lógica inteligente para cruzar nombres cortos con nombres largos de la BD
        $filtroLimpio = str_replace([' - ', ' ', '-'], ['%', '%', '%'], trim($producto_filtro));

        // --- FILTRO VENTAS INTERNAS ---
        $queryVentas = Venta::with(['detalles.producto'])
                            ->whereBetween('fecha', [$fecha_inicio, $fecha_fin]);
                            
        if ($movimiento !== 'todos') $queryVentas->where('movimiento', $movimiento);
        if ($vendedor !== 'todos') $queryVentas->where('vendedor', 'LIKE', '%' . trim($vendedor) . '%');
        if ($producto_filtro !== 'todos') {
            $queryVentas->whereHas('detalles.producto', function($q) use ($filtroLimpio, $producto_filtro) {
                $q->where('nombre', 'LIKE', '%' . $filtroLimpio . '%')
                  ->orWhere('nombre', 'LIKE', '%' . trim($producto_filtro) . '%');
            });
        }
        
        $ventas = $queryVentas->orderBy('fecha', 'desc')->get();

        $gastos = collect();
        $totalGastos = 0;
        
        try {
            if (class_exists('App\Models\Gasto') && Schema::hasTable('gastos')) {
                $gastos = Gasto::whereBetween('fecha', [$fecha_inicio, $fecha_fin])
                               ->orderBy('fecha', 'asc')
                               ->get();
                $totalGastos = $gastos->sum('monto');
            }
        } catch (\Exception $e) { }

        // --- FILTRO VENTAS EXTERNAS ---
        $ventasFuera = collect();
        $totalFuera = 0;
        
        try {
            if (class_exists('App\Models\VentaExterna') && Schema::hasTable('ventas_externas')) {
                $queryFuera = \App\Models\VentaExterna::whereBetween('fecha', [$fecha_inicio_fuera, $fecha_fin_fuera]);
                
                if ($movimiento !== 'todos') $queryFuera->where('movimiento', $movimiento);
                if ($vendedor !== 'todos') $queryFuera->where('vendedor', 'LIKE', '%' . trim($vendedor) . '%');
                if ($producto_filtro !== 'todos') {
                    $queryFuera->where('producto', 'LIKE', '%' . trim($producto_filtro) . '%')
                               ->orWhere('producto', 'LIKE', '%' . $filtroLimpio . '%');
                }

                $ventasFuera = $queryFuera->orderBy('fecha', 'desc')->get();
                $totalFuera = $ventasFuera->sum('total_facturado');
            }
        } catch (\Exception $e) { }

        $todasLasVentas = collect();

        foreach ($ventas as $v) {
            if (!empty($v->vendedor)) {
                foreach ($v->detalles as $detalle) {
                    $todasLasVentas->push([
                        'vendedor' => trim($v->vendedor),
                        'producto' => $detalle->producto ? trim($detalle->producto->nombre) : 'Sin Nombre',
                        'cantidad' => $detalle->cantidad,
                        'tonelada' => $detalle->tonelada ?? 0,
                        'precio_unitario' => $detalle->precio_unitario,
                        'total_facturado' => $detalle->subtotal,
                    ]);
                }
            }
        }

        foreach ($ventasFuera as $vf) {
            if (!empty($vf->vendedor)) {
                $todasLasVentas->push([
                    'vendedor' => trim($vf->vendedor),
                    'producto' => trim($vf->producto),
                    'cantidad' => $vf->cantidad,
                    'tonelada' => $vf->tonelada ?? 0,
                    'precio_unitario' => $vf->precio_unitario,
                    'total_facturado' => $vf->total_facturado,
                ]);
            }
        }

        $consolidadoVendedores = collect();
        if ($todasLasVentas->isNotEmpty()) {
            $consolidadoVendedores = $todasLasVentas->groupBy('vendedor')->map(function ($ventasVendedor) {
                return $ventasVendedor->groupBy('producto')->map(function ($ventasProducto) {
                    return [
                        'cantidad' => $ventasProducto->sum('cantidad'),
                        'tonelada' => $ventasProducto->sum('tonelada'),
                        'precio_unitario' => $ventasProducto->first()['precio_unitario'],
                        'total_facturado' => $ventasProducto->sum('total_facturado'),
                    ];
                });
            });
        }

        return view('ventas.index', compact(
            'ventas', 'gastos', 'totalGastos', 
            'fecha_inicio', 'fecha_fin', 'movimiento', 'vendedor', 'producto_filtro',
            'ventasFuera', 'totalFuera', 'fecha_inicio_fuera', 'fecha_fin_fuera',
            'consolidadoVendedores'
        ));
    }

    public function create()
    {
        $rol = Auth::user()->rol;
        if ($rol !== 'admin' && $rol !== 'ventas') abort(403);
        
        $clientes = Venta::select('cliente')->whereNotNull('cliente')->distinct()->get();
        $productos = Producto::all(); 
        
        return view('ventas.create', compact('clientes', 'productos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date', 
            'almacen_origen' => 'required|string|in:Tarma,Lima,Lambayeque', 
            'cliente' => 'required',
            'total_facturado' => 'required|numeric',
            'producto_id' => 'required|array',
            'cantidad' => 'required|array',
        ]);

        $productos_id = $request->producto_id;
        $cantidades = $request->cantidad;
        $almacenSeleccionado = $request->almacen_origen;

        for ($i = 0; $i < count($productos_id); $i++) {
            $producto = Producto::find($productos_id[$i]);
            
            if ($producto) {
                $stockDisponible = 0;
                if ($almacenSeleccionado === 'Tarma') $stockDisponible = $producto->stock_tarma;
                elseif ($almacenSeleccionado === 'Lima') $stockDisponible = $producto->stock_lima;
                elseif ($almacenSeleccionado === 'Lambayeque') $stockDisponible = $producto->stock_lambayeque;

                if ($cantidades[$i] > $stockDisponible) {
                    return redirect()->back()
                        ->withInput() 
                        ->with('error', '🚨 STOCK INSUFICIENTE EN ' . strtoupper($almacenSeleccionado) . ': No puedes vender ' . $cantidades[$i] . ' unidades de "' . $producto->nombre . '". El stock actual en esa ubicación es de ' . $stockDisponible . ' unidades.');
                }
            } else {
                return redirect()->back()->withInput()->with('error', 'Error: Uno de los productos seleccionados ya no existe.');
            }
        }

        // LÓGICA DE CONTROL DE CRÉDITO - ENTERO INTEGRADO
        $plazo_dias = null;
        $fecha_vencimiento = null;
        $cancelado = true;

        if (in_array($request->forma_pago, ['Credito', 'Mixto'])) {
            $plazo_dias = (int) $request->input('plazo_dias', 15);
            $fecha_vencimiento = Carbon::parse($request->fecha)->addDays($plazo_dias)->toDateString();
            $cancelado = false;
        }

        DB::beginTransaction();

        try {
            if ($request->filled('ruc')) {
                Cliente::updateOrCreate(
                    ['ruc' => trim($request->ruc)],
                    [
                        'razon_social' => strtoupper($request->cliente),
                        'lugar'        => strtoupper($request->lugar ?? ''),
                    ]
                );
            }

            $venta = Venta::create([
                'fecha' => $request->fecha,
                'almacen_origen' => $almacenSeleccionado, 
                'tipo_comprobante' => $request->tipo_comprobante,
                'numero_comprobante' => $request->numero_comprobante,
                'codigo_guia' => $request->codigo_guia,
                'numero_guia' => $request->numero_guia,
                'cliente' => strtoupper($request->cliente), 
                'detalle' => $request->detalle,
                'vendedor' => $request->vendedor,
                'zona' => $request->zona,
                'lugar' => strtoupper($request->lugar ?? ''),
                'con_igv' => $request->boolean('con_igv'),
                'total_facturado' => $request->total_facturado,
                
                'aplica_detraccion' => $request->has('aplica_detraccion'),
                'tipo_detraccion' => $request->tipo_detraccion,
                'monto_detraccion' => $request->monto_detraccion,
                'fecha_pago_detraccion' => $request->fecha_pago_detraccion,

                'forma_pago' => $request->forma_pago,
                'monto_contado' => $request->monto_contado ?? 0,
                'monto_credito' => $request->monto_credito ?? 0,
                'movimiento' => $request->movimiento,

                'plazo_dias' => $plazo_dias,
                'fecha_vencimiento' => $fecha_vencimiento,
                'cancelado' => $cancelado,
            ]);

            $toneladas = $request->tonelada;
            $precios = $request->precio_unitario;

            for ($i = 0; $i < count($productos_id); $i++) {
                $cantidad_vendida = $cantidades[$i];
                $precio_unit = $precios[$i];
                $subtotal = $cantidad_vendida * $precio_unit;

                VentaDetalle::create([
                    'venta_id' => $venta->id,
                    'producto_id' => $productos_id[$i],
                    'cantidad' => $cantidad_vendida,
                    'tonelada' => $toneladas[$i] ?? 0,
                    'precio_unitario' => $precio_unit,
                    'subtotal' => $subtotal,
                ]);

                $producto_en_almacen = Producto::find($productos_id[$i]);
                if ($producto_en_almacen) {
                    if ($almacenSeleccionado === 'Tarma') $producto_en_almacen->decrement('stock_tarma', $cantidad_vendida);
                    elseif ($almacenSeleccionado === 'Lima') $producto_en_almacen->decrement('stock_lima', $cantidad_vendida);
                    elseif ($almacenSeleccionado === 'Lambayeque') $producto_en_almacen->decrement('stock_lambayeque', $cantidad_vendida);
                    
                    $producto_en_almacen->decrement('stock', $cantidad_vendida);
                }
            }

            DB::commit();
            return redirect()->route('ventas.index')->with('success', 'Venta registrada. Se descontó stock del almacén de ' . strtoupper($almacenSeleccionado));

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Error interno al guardar la venta: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $rol = Auth::user()->rol;
        if ($rol !== 'admin' && $rol !== 'ventas') abort(403);

        $venta = Venta::with('detalles.producto')->findOrFail($id);
        $clientes = Venta::select('cliente')->whereNotNull('cliente')->distinct()->get();
        $productos = Producto::all(); 
        
        return view('ventas.edit', compact('venta', 'clientes', 'productos'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'fecha' => 'required|date', 
            'almacen_origen' => 'required|string|in:Tarma,Lima,Lambayeque',
            'cliente' => 'required',
            'total_facturado' => 'required|numeric',
            'producto_id' => 'required|array',
            'cantidad' => 'required|array',
        ]);

        $venta = Venta::with('detalles')->findOrFail($id);
        $almacenViejo = $venta->almacen_origen ?? 'Tarma'; 
        $almacenNuevo = $request->almacen_origen;

        // LÓGICA DE CONTROL DE CRÉDITO - ENTERO INTEGRADO
        $plazo_dias = null;
        $fecha_vencimiento = null;
        $cancelado = true;

        if (in_array($request->forma_pago, ['Credito', 'Mixto'])) {
            $plazo_dias = (int) $request->input('plazo_dias', 15);
            $fecha_vencimiento = Carbon::parse($request->fecha)->addDays($plazo_dias)->toDateString();
            $cancelado = false;
        }

        DB::beginTransaction();

        try {
            foreach ($venta->detalles as $detalleOriginal) {
                $producto = Producto::find($detalleOriginal->producto_id);
                if ($producto) {
                    if ($almacenViejo === 'Tarma') $producto->increment('stock_tarma', $detalleOriginal->cantidad);
                    elseif ($almacenViejo === 'Lima') $producto->increment('stock_lima', $detalleOriginal->increment);
                    elseif ($almacenViejo === 'Lambayeque') $producto->increment('stock_lambayeque', $detalleOriginal->cantidad);
                    
                    $producto->increment('stock', $detalleOriginal->cantidad);
                }
            }

            $nuevos_productos_id = $request->producto_id;
            $nuevas_cantidades = $request->cantidad;

            for ($i = 0; $i < count($nuevos_productos_id); $i++) {
                $producto = Producto::find($nuevos_productos_id[$i]);
                if ($producto) {
                    $stockDisponible = 0;
                    if ($almacenNuevo === 'Tarma') $stockDisponible = $producto->stock_tarma;
                    elseif ($almacenNuevo === 'Lima') $stockDisponible = $producto->stock_lima;
                    elseif ($almacenNuevo === 'Lambayeque') $stockDisponible = $producto->stock_lambayeque;

                    if ($nuevas_cantidades[$i] > $stockDisponible) {
                        DB::rollBack(); 
                        return redirect()->back()->withInput()->with('error', '🚨 STOCK INSUFICIENTE EN ' . strtoupper($almacenNuevo) . ': No puedes vender ' . $nuevas_cantidades[$i] . ' unidades de "' . $producto->nombre . '". Stock actual disponible: ' . $stockDisponible);
                    }
                }
            }

            if ($request->filled('ruc')) {
                Cliente::updateOrCreate(
                    ['ruc' => trim($request->ruc)],
                    ['razon_social' => strtoupper($request->cliente), 'lugar' => strtoupper($request->lugar ?? '')]
                );
            }

            $venta->update([
                'fecha' => $request->fecha,
                'almacen_origen' => $almacenNuevo,
                'tipo_comprobante' => $request->tipo_comprobante,
                'numero_comprobante' => $request->numero_comprobante,
                'codigo_guia' => $request->codigo_guia,
                'numero_guia' => $request->numero_guia,
                'cliente' => strtoupper($request->cliente), 
                'detalle' => $request->detalle,
                'vendedor' => $request->vendedor,
                'zona' => $request->zona,
                'lugar' => strtoupper($request->lugar ?? ''),
                'con_igv' => $request->boolean('con_igv'),
                'total_facturado' => $request->total_facturado,
                'aplica_detraccion' => $request->has('aplica_detraccion'),
                'tipo_detraccion' => $request->tipo_detraccion,
                'monto_detraccion' => $request->monto_detraccion,
                'fecha_pago_detraccion' => $request->fecha_pago_detraccion,
                'forma_pago' => $request->forma_pago,
                'monto_contado' => $request->monto_contado ?? 0,
                'monto_credito' => $request->monto_credito ?? 0,
                'movimiento' => $request->movimiento,

                'plazo_dias' => $plazo_dias,
                'fecha_vencimiento' => $fecha_vencimiento,
                'cancelado' => $cancelado,
            ]);

            $venta->detalles()->delete();

            $nuevas_toneladas = $request->tonelada;
            $nuevos_precios = $request->precio_unitario;

            for ($i = 0; $i < count($nuevos_productos_id); $i++) {
                $cant = $nuevas_cantidades[$i];
                $prec = $nuevos_precios[$i];
                
                VentaDetalle::create([
                    'venta_id' => $venta->id,
                    'producto_id' => $nuevos_productos_id[$i],
                    'cantidad' => $cant,
                    'tonelada' => $nuevas_toneladas[$i] ?? 0,
                    'precio_unitario' => $prec,
                    'subtotal' => $cant * $prec,
                ]);

                $prod = Producto::find($nuevos_productos_id[$i]);
                if($prod) {
                    if ($almacenNuevo === 'Tarma') $prod->decrement('stock_tarma', $cant);
                    elseif ($almacenNuevo === 'Lima') $prod->decrement('stock_lima', $cant);
                    elseif ($almacenNuevo === 'Lambayeque') $prod->decrement('stock_lambayeque', $cant);
                    
                    $prod->decrement('stock', $cant);
                }
            }

            DB::commit();
            return redirect()->route('ventas.index')->with('success', 'Venta modificada exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Error al editar la venta: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $venta = Venta::with('detalles')->findOrFail($id);
            $almacen = $venta->almacen_origen ?? 'Tarma';

            foreach ($venta->detalles as $detalle) {
                $producto = Producto::find($detalle->producto_id);
                if ($producto) {
                    if ($almacen === 'Tarma') $producto->increment('stock_tarma', $detalle->cantidad);
                    elseif ($almacen === 'Lima') $producto->increment('stock_lima', $detalle->cantidad);
                    elseif ($almacen === 'Lambayeque') $producto->increment('stock_lambayeque', $detalle->cantidad);
                    
                    $producto->increment('stock', $detalle->cantidad);
                }
            }

            $venta->detalles()->delete();
            $venta->delete();

            DB::commit();
            return redirect()->back()->with('success', 'Venta anulada. El stock ha regresado al almacén de ' . strtoupper($almacen));

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al anular la venta: ' . $e->getMessage());
        }
    }

    public function createGasto() { return view('gastos.create'); }
    
    public function storeGasto(Request $request) { 
        $request->validate(['fecha' => 'required|date', 'detalle' => 'required|string|max:255', 'monto' => 'required|numeric|min:0']);
        try {
            Gasto::create(['fecha' => $request->fecha, 'detalle' => strtoupper($request->detalle), 'monto' => $request->monto]);
            return redirect()->route('ventas.index')->with('success', 'Gasto operativo registrado.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }
    
    public function destroyGasto($id) { 
        try { Gasto::findOrFail($id)->delete(); return redirect()->back()->with('success', 'Gasto eliminado.'); } catch (\Exception $e) { return redirect()->back()->with('error', 'Error: ' . $e->getMessage()); }
    }
    
    // ==========================================
    // --- ZONA DE VENTAS EXTERNAS ---
    // ==========================================
    
    public function createFuera() 
    { 
        $rol = Auth::user()->rol;
        if ($rol !== 'admin' && $rol !== 'ventas') abort(403);
        
        $productos = Producto::all();
        
        return view('ventas_fuera.create', compact('productos')); 
    }
    
    public function storeFuera(Request $request) { 
        $request->validate([
            'fecha' => 'required|date', 
            'tipo_comprobante' => 'required|string', 
            'numero_comprobante' => 'required|string', 
            'cliente' => 'required|string', 
            'vendedor' => 'required|string', 
            'zona' => 'required|string', 
            'lugar' => 'required|string', 
            'producto' => 'required|string', 
            'cantidad' => 'required|numeric', 
            'precio_unitario' => 'required|numeric', 
            'total_facturado' => 'required|numeric'
        ]);

        // LÓGICA DE CONTROL DE CRÉDITO - ENTERO INTEGRADO
        $plazo_dias = null;
        $fecha_vencimiento = null;
        $cancelado = true;

        if (in_array($request->forma_pago, ['Credito', 'Mixto'])) {
            $plazo_dias = (int) $request->input('plazo_dias', 15);
            $fecha_vencimiento = Carbon::parse($request->fecha)->addDays($plazo_dias)->toDateString();
            $cancelado = false;
        }

        try {
            \App\Models\VentaExterna::create([
                'fecha' => $request->fecha, 
                'tipo_comprobante' => strtoupper($request->tipo_comprobante), 
                'numero_comprobante' => strtoupper($request->numero_comprobante), 
                'codigo_guia' => strtoupper($request->codigo_guia ?? ''), 
                'numero_guia' => strtoupper($request->numero_guia ?? ''), 
                'cliente' => strtoupper($request->cliente), 
                'vendedor' => $request->vendedor, 
                'zona' => $request->zona, 
                'lugar' => strtoupper($request->lugar), 
                'producto' => $request->producto, 
                'cantidad' => $request->cantidad, 
                'tonelada' => $request->tonelada ?? 0, 
                'precio_unitario' => $request->precio_unitario, 
                'con_igv' => $request->boolean('con_igv'),
                'total_facturado' => $request->total_facturado, 
                'detalle' => strtoupper($request->detalle ?? ''),
                'forma_pago' => $request->forma_pago ?? 'Contado',
                'monto_contado' => $request->monto_contado ?? 0,
                'monto_credito' => $request->monto_credito ?? 0,
                'movimiento' => $request->movimiento ?? 'CAJA',

                'plazo_dias' => $plazo_dias,
                'fecha_vencimiento' => $fecha_vencimiento,
                'cancelado' => $cancelado,
            ]);
            return redirect()->route('ventas.index')->with('success', 'Venta externa registrada correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function editFuera($id) {
        $rol = Auth::user()->rol;
        if ($rol !== 'admin' && $rol !== 'ventas') abort(403);
        
        $ventaFuera = \App\Models\VentaExterna::findOrFail($id);
        return view('ventas_fuera.edit', compact('ventaFuera'));
    }

    public function updateFuera(Request $request, $id) {
        $rol = Auth::user()->rol;
        if ($rol !== 'admin' && $rol !== 'ventas') abort(403);

        $request->validate([
            'fecha' => 'required|date', 
            'tipo_comprobante' => 'required|string', 
            'numero_comprobante' => 'required|string', 
            'cliente' => 'required|string', 
            'vendedor' => 'required|string', 
            'zona' => 'required|string', 
            'lugar' => 'required|string', 
            'producto' => 'required|string', 
            'cantidad' => 'required|numeric', 
            'precio_unitario' => 'required|numeric', 
            'total_facturado' => 'required|numeric'
        ]);

        // LÓGICA DE CONTROL DE CRÉDITO - ENTERO INTEGRADO
        $plazo_dias = null;
        $fecha_vencimiento = null;
        $cancelado = true;

        if (in_array($request->forma_pago, ['Credito', 'Mixto'])) {
            $plazo_dias = (int) $request->input('plazo_dias', 15);
            $fecha_vencimiento = Carbon::parse($request->fecha)->addDays($plazo_dias)->toDateString();
            $cancelado = false;
        }

        try {
            $ventaFuera = \App\Models\VentaExterna::findOrFail($id);
            $ventaFuera->update([
                'fecha' => $request->fecha, 
                'tipo_comprobante' => strtoupper($request->tipo_comprobante), 
                'numero_comprobante' => strtoupper($request->numero_comprobante), 
                'codigo_guia' => strtoupper($request->codigo_guia ?? ''), 
                'numero_guia' => strtoupper($request->numero_guia ?? ''), 
                'cliente' => strtoupper($request->cliente), 
                'vendedor' => $request->vendedor, 
                'zona' => $request->zona, 
                'lugar' => strtoupper($request->lugar), 
                'producto' => $request->producto, 
                'cantidad' => $request->cantidad, 
                'tonelada' => $request->tonelada ?? 0, 
                'precio_unitario' => $request->precio_unitario, 
                'con_igv' => $request->boolean('con_igv'),
                'total_facturado' => $request->total_facturado, 
                'detalle' => strtoupper($request->detalle ?? ''),
                'forma_pago' => $request->forma_pago ?? 'Contado',
                'monto_contado' => $request->monto_contado ?? 0,
                'monto_credito' => $request->monto_credito ?? 0,
                'movimiento' => $request->movimiento ?? 'CAJA',

                'plazo_dias' => $plazo_dias,
                'fecha_vencimiento' => $fecha_vencimiento,
                'cancelado' => $cancelado,
            ]);
            return redirect()->route('ventas.index')->with('success', 'Venta externa actualizada correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }
    
    public function destroyFuera($id) { 
        try { \App\Models\VentaExterna::findOrFail($id)->delete(); return redirect()->back()->with('success', 'Venta externa eliminada.'); } catch (\Exception $e) { return redirect()->back()->with('error', 'Error: ' . $e->getMessage()); }
    }

    // ==========================================
    // --- MANEJO DE NOTIFICACIONES Y DEUDAS ---
    // ==========================================

    public function getNotificaciones()
    {
        $notificaciones = auth()->user()->unreadNotifications;
        return response()->json($notificaciones);
    }

    public function marcarAsRead($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        return response()->json(['success' => true]);
    }

    public function cancelarCredito(Request $request, $id)
    {
        $tipo = $request->input('tipo_venta');
        $notificationId = $request->input('notification_id');

        // 1. Buscamos y actualizamos la venta correspondiente a pagada
        if ($tipo === 'Interna') {
            $venta = Venta::findOrFail($id);
        } else {
            $venta = \App\Models\VentaExterna::findOrFail($id);
        }

        $venta->update(['cancelado' => true]);

        // 2. Si se procesa desde la campana, marcamos la notificación como leída automáticamente
        if ($notificationId) {
            $notification = auth()->user()->notifications()->find($notificationId);
            if ($notification) {
                $notification->markAsRead();
            }
        }

        // 3. Responder de manera asíncrona si es una petición AJAX/Fetch
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true, 
                'message' => '¡El crédito ha sido marcado como pagado y la alerta fue removida!'
            ]);
        }

        return redirect()->back()->with('success', '¡El crédito ha sido marcado como cancelado/pagado con éxito!');
    }
}