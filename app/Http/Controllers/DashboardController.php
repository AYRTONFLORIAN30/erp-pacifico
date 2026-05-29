<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\VentaExterna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. CAPTURAR EL FILTRO (Si no hay filtro, usa el mes y año actual)
        $mes = $request->get('mes', now()->month);
        $anio = $request->get('anio', now()->year);

        // =====================================================================
        // 2. KPI'S (Indicadores Generales - SUMANDO AMBAS TABLAS)
        // =====================================================================
        $totalInternas = (float) Venta::whereMonth('fecha', $mes)->whereYear('fecha', $anio)->sum('total_facturado');
        $totalExternas = (float) VentaExterna::whereMonth('fecha', $mes)->whereYear('fecha', $anio)->sum('total_facturado');
        $totalVentasSoles = $totalInternas + $totalExternas;

        $conteoInternas = (int) Venta::whereMonth('fecha', $mes)->whereYear('fecha', $anio)->count();
        $conteoExternas = (int) VentaExterna::whereMonth('fecha', $mes)->whereYear('fecha', $anio)->count();
        $conteoVentas = $conteoInternas + $conteoExternas;

        // Las detracciones solo vienen de ventas internas
        $montoDetracciones = (float) Venta::whereMonth('fecha', $mes)->whereYear('fecha', $anio)->sum('monto_detraccion');


        // =====================================================================
        // 3. GRÁFICO 1: TOP CLIENTES (Uniendo Internas + Externas) - SIN LÍMITE
        // =====================================================================
        $clientesInt = DB::table('ventas')
            ->select('cliente', 'total_facturado')
            ->whereMonth('fecha', $mes)->whereYear('fecha', $anio)
            ->whereNotNull('cliente');

        $clientesExt = DB::table('ventas_externas')
            ->select('cliente', 'total_facturado')
            ->whereMonth('fecha', $mes)->whereYear('fecha', $anio)
            ->whereNotNull('cliente');

        // Unimos ambas tablas virtualmente
        $unionClientes = $clientesInt->unionAll($clientesExt);

        // Agrupamos y sumamos usando la tabla virtual (SE QUITÓ EL ->limit(7))
        $dataClientes = DB::query()
            ->fromSub($unionClientes, 'unidas')
            ->select('cliente', DB::raw('SUM(total_facturado) as total'))
            ->groupBy('cliente')
            ->orderByDesc('total')
            ->get(); // <-- Trae TODOS los clientes del mes

        $clientesNombres = $dataClientes->map(function($item) {
            return strlen($item->cliente) > 25 ? substr($item->cliente, 0, 25) . '...' : $item->cliente;
        })->toArray();
        $clientesMontos = $dataClientes->pluck('total')->map(function($item) { return (float) $item; })->toArray();


        // =====================================================================
        // 4. GRÁFICO 2: VENTAS POR ZONA (Uniendo Internas + Externas)
        // =====================================================================
        $zonasInt = DB::table('ventas')
            ->select('zona', 'total_facturado')
            ->whereMonth('fecha', $mes)->whereYear('fecha', $anio)
            ->whereNotNull('zona')->where('zona', '!=', '');

        $zonasExt = DB::table('ventas_externas')
            ->select('zona', 'total_facturado')
            ->whereMonth('fecha', $mes)->whereYear('fecha', $anio)
            ->whereNotNull('zona')->where('zona', '!=', '');

        $unionZonas = $zonasInt->unionAll($zonasExt);

        $dataZonas = DB::query()
            ->fromSub($unionZonas, 'unidas_zonas')
            ->select('zona', DB::raw('SUM(total_facturado) as total'))
            ->groupBy('zona')
            ->orderByDesc('total')
            ->get();

        $zonasNombres = $dataZonas->pluck('zona')->toArray();
        $zonasMontos = $dataZonas->pluck('total')->map(function($item) { return (float) $item; })->toArray();


        // =====================================================================
        // 5. GRÁFICO 3: EVOLUCIÓN DIARIA (Uniendo Internas + Externas)
        // =====================================================================
        $diasInt = DB::table('ventas')
            ->select(DB::raw('DAY(fecha) as dia'), 'total_facturado')
            ->whereMonth('fecha', $mes)->whereYear('fecha', $anio);

        $diasExt = DB::table('ventas_externas')
            ->select(DB::raw('DAY(fecha) as dia'), 'total_facturado')
            ->whereMonth('fecha', $mes)->whereYear('fecha', $anio);

        $unionDias = $diasInt->unionAll($diasExt);

        $dataVentasDiarias = DB::query()
            ->fromSub($unionDias, 'unidas_dias')
            ->select('dia', DB::raw('SUM(total_facturado) as total'))
            ->groupBy('dia')
            ->orderBy('dia')
            ->get();

        // Creamos un array con todos los días del mes llenos de 0
        $diasEnElMes = Carbon::createFromDate($anio, $mes, 1)->daysInMonth;
        $ventasPorDia = array_fill(1, $diasEnElMes, 0);

        // Reemplazamos los 0 con las ventas reales (SUMADAS)
        foreach ($dataVentasDiarias as $vd) {
            $ventasPorDia[$vd->dia] = (float) $vd->total;
        }

        $diasLabels = array_keys($ventasPorDia);
        $diasMontos = array_values($ventasPorDia);

        return view('dashboard', compact(
            'totalVentasSoles',
            'conteoVentas',
            'montoDetracciones',
            'clientesNombres',
            'clientesMontos',
            'zonasNombres',
            'zonasMontos',
            'diasLabels',
            'diasMontos',
            'mes',
            'anio'
        ));
    }
}