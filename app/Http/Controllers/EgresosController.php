<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Egreso;
use App\Models\Proveedor;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EgresosController extends Controller
{
    public function index(Request $request)
    {
        $rol = Auth::user()->rol;
        if ($rol !== 'admin' && $rol !== 'egresos') {
             abort(403, 'ACCESO DENEGADO: No tienes permiso para ver Egresos.');
        }

        // Fechas por defecto: Del día 1 del mes actual, hasta el día de hoy
        $defaultInicio = now()->startOfMonth()->format('Y-m-d');
        $defaultFin = now()->format('Y-m-d');

        // Capturamos los nuevos filtros
        $fecha_inicio = $request->get('fecha_inicio', $defaultInicio);
        $fecha_fin = $request->get('fecha_fin', $defaultFin);
        $metodo = $request->get('metodo', 'todos');

        // Consulta filtrando por el rango de fechas
        $query = Egreso::whereBetween('fecha_emision', [$fecha_inicio, $fecha_fin]);

        if ($metodo !== 'todos') {
            $query->where('metodo_pago', $metodo);
        }

        $egresos = $query->orderBy('fecha_emision', 'desc')->get();

        return view('egresos.index', compact('egresos', 'fecha_inicio', 'fecha_fin', 'metodo'));
    }

    public function create()
    {
        $rol = Auth::user()->rol;
        if ($rol !== 'admin' && $rol !== 'egresos') {
             abort(403, 'ACCESO DENEGADO');
        }

        $proveedores = Proveedor::select('ruc', 'razon_social')
                            ->orderBy('razon_social', 'asc')
                            ->get();

        return view('egresos.create', compact('proveedores'));
    }

    public function store(Request $request)
    {
        $rol = Auth::user()->rol;
        if ($rol !== 'admin' && $rol !== 'egresos') {
             abort(403, 'ACCESO DENEGADO');
        }

        $request->validate([
            'fecha' => 'required|date',
            'ruc' => 'required',
            'razon_social' => 'required',
            'monto_ingresado' => 'required|numeric',
        ]);

        Egreso::create([
            'fecha_emision'    => $request->fecha,
            'descripcion'      => $request->descripcion,
            'glosa'            => strtoupper($request->glosa),
            'ruc'              => trim($request->ruc),
            'razon_social'     => strtoupper($request->razon_social),
            'n_comprobante'    => strtoupper($request->n_comprobante),
            'guia'             => strtoupper($request->guia),
            'estado_guia'      => strtoupper($request->estado_guia),
            'nc_nd'            => strtoupper($request->nc_nd),
            'total'            => $request->total_final,
            'base_imponible'   => $request->base_imponible ?? 0,
            'igv'              => $request->igv ?? 0,
            'no_gravado'       => $request->monto_no_gravado ?? 0,
            'otras_tasas'      => $request->check_tc ? $request->tasa_cambio : 0, 
            'detraccion_monto' => $request->detraccion_monto ?? 0,
            'detraccion_fecha' => $request->detraccion_fecha,
            'estado_pago'      => $request->estado_pago,
            'metodo_pago'      => $request->metodo_pago, 
            'saldo_pendiente'  => $request->saldo_pendiente ?? 0,
            'responsable'      => $request->responsable ?? Auth::user()->name,
        ]);

        if ($request->filled('ruc')) {
            Proveedor::updateOrCreate(
                ['ruc' => trim($request->ruc)],
                ['razon_social' => strtoupper($request->razon_social)]
            );
        }

        return redirect()->route('egresos.index')->with('success', '¡Operación registrada correctamente!');
    }

    public function edit($id)
    {
        $rol = Auth::user()->rol;
        if ($rol !== 'admin' && $rol !== 'egresos') abort(403);

        $egreso = Egreso::findOrFail($id);
        $proveedores = Proveedor::select('ruc', 'razon_social')->orderBy('razon_social', 'asc')->get();
        
        return view('egresos.edit', compact('egreso', 'proveedores'));
    }

    public function update(Request $request, $id)
    {
        $rol = Auth::user()->rol;
        if ($rol !== 'admin' && $rol !== 'egresos') abort(403);

        $request->validate([
            'fecha' => 'required|date',
            'ruc' => 'required',
            'razon_social' => 'required',
            'monto_ingresado' => 'required|numeric',
        ]);

        $egreso = Egreso::findOrFail($id);

        $egreso->update([
            'fecha_emision'    => $request->fecha,
            'descripcion'      => $request->descripcion,
            'glosa'            => strtoupper($request->glosa),
            'ruc'              => trim($request->ruc),
            'razon_social'     => strtoupper($request->razon_social),
            'n_comprobante'    => strtoupper($request->n_comprobante),
            'guia'             => strtoupper($request->guia),
            'estado_guia'      => strtoupper($request->estado_guia),
            'nc_nd'            => strtoupper($request->nc_nd),
            'total'            => $request->total_final,
            'base_imponible'   => $request->base_imponible ?? 0,
            'igv'              => $request->igv ?? 0,
            'no_gravado'       => $request->monto_no_gravado ?? 0,
            'otras_tasas'      => $request->check_tc ? $request->tasa_cambio : 0, 
            'detraccion_monto' => $request->detraccion_monto ?? 0,
            'detraccion_fecha' => $request->detraccion_fecha,
            'estado_pago'      => $request->estado_pago,
            'metodo_pago'      => $request->metodo_pago, 
            'saldo_pendiente'  => $request->saldo_pendiente ?? 0,
            'responsable'      => $request->responsable ?? Auth::user()->name,
        ]);

        if ($request->filled('ruc')) {
            Proveedor::updateOrCreate(
                ['ruc' => trim($request->ruc)],
                ['razon_social' => strtoupper($request->razon_social)]
            );
        }

        return redirect()->route('egresos.index')->with('success', '¡Egreso modificado exitosamente!');
    }

    public function destroy($id)
    {
        $rol = Auth::user()->rol;
        if ($rol !== 'admin' && $rol !== 'egresos') abort(403);

        try {
            $egreso = Egreso::findOrFail($id);
            $egreso->delete();
            return redirect()->route('egresos.index')->with('success', 'Egreso eliminado correctamente del sistema.');
        } catch (\Exception $e) {
            return redirect()->route('egresos.index')->with('error', 'Error al eliminar el egreso: ' . $e->getMessage());
        }
    }

    public function buscarRuc(Request $request)
    {
        $ruc = $request->query('ruc');
        $proveedor = Proveedor::where('ruc', $ruc)->first();

        if ($proveedor) {
            return response()->json([
                'encontrado' => true,
                'razon_social' => $proveedor->razon_social
            ]);
        }
        return response()->json(['encontrado' => false]);
    }

    public function importarProveedores(Request $request)
    {
        $request->validate([
            'archivo_csv' => 'required|mimes:csv,txt'
        ]);

        $path = $request->file('archivo_csv')->getRealPath();
        $file = fopen($path, 'r');
        $firstLine = fgets($file);
        $delimiter = strpos($firstLine, ';') !== false ? ';' : ',';
        rewind($file); 

        $contador = 0;
        
        while (($datos = fgetcsv($file, 10000, $delimiter)) !== FALSE) {
            $ruc = null;
            $razon_social = null;

            foreach ($datos as $index => $columna) {
                $valor = trim($columna);
                
                if (is_numeric($valor) && (strlen($valor) == 11 || strlen($valor) == 8)) {
                    $ruc = $valor;
                    if (isset($datos[$index + 1])) {
                        $razon_social = strtoupper(trim($datos[$index + 1]));
                    } else {
                        $razon_social = 'SIN NOMBRE';
                    }
                    break; 
                }
            }

            if ($ruc && !empty($razon_social) && $razon_social !== 'SIN NOMBRE') {
                Proveedor::updateOrCreate(
                    ['ruc' => $ruc], 
                    ['razon_social' => $razon_social] 
                );
                $contador++;
            }
        }
        
        fclose($file);
        return redirect()->back()->with('success', "¡Base de datos actualizada! Se escanearon e importaron {$contador} proveedores correctamente.");
    }
}

