<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EgresosController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\DashboardController; // Importado para el nuevo Dashboard
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan; // ✅ IMPORTANTE: Añadido para que funcione la ruta de emergencia
use Illuminate\Http\Request;

Route::get('/', function () {
    if (Auth::check()) {
        $rol = Auth::user()->rol;
        
        // Admin y Ventas entran directo al Dashboard al iniciar sesión
        if ($rol === 'admin' || $rol === 'ventas') return redirect()->route('dashboard');
        
        if ($rol === 'egresos') return redirect()->route('egresos.index');
        if ($rol === 'almacen') return redirect()->route('inventario.index');
        
        return redirect()->route('en_construccion');
    }
    return redirect()->route('login');
});

Route::get('/construccion', function () {
    return view('construccion');
})->middleware('auth')->name('en_construccion');

Route::middleware('auth')->group(function () {
    
    // --- PERFIL DE USUARIO ---
    Mr::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- DASHBOARD VISUAL (GRÁFICOS) CON REDIRECCIÓN INTELIGENTE ---
    Route::get('/dashboard', function (Request $request) {
        $rol = Auth::user()->rol;
        
        // 🚫 BLOQUEAMOS SOLO A EGRESOS Y ALMACÉN
        if ($rol === 'egresos') {
            return redirect()->route('egresos.index');
        } elseif ($rol === 'almacen') {
            return redirect()->route('inventario.index');
        }

        // ✅ PERMITIMOS EL PASO A ADMIN Y VENTAS PARA VER LOS GRÁFICOS
        return app(DashboardController::class)->index($request);
        
    })->name('dashboard');

    // --- ZONA DE CAJA / EGRESOS ---
    // 🚀 Rutas personalizadas (Buscar RUC e Importar Excel) VAN ANTES del resource
    Route::get('/egresos/buscar-ruc', [EgresosController::class, 'buscarRuc'])->name('egresos.buscarRuc');
    Route::post('/egresos/importar-proveedores', [EgresosController::class, 'importarProveedores'])->name('egresos.importar_proveedores');
    Route::resource('egresos', EgresosController::class);

    // --- ZONA DE VENTAS INTERNAS ---
    // Incluye: index, create, store, edit, update y destroy (anular)
    Route::resource('ventas', VentaController::class);

    // --- ZONA DE GASTOS OPERATIVOS DE PLANTA ---
    Route::get('/gastos/crear', [VentaController::class, 'createGasto'])->name('gastos.create'); 
    Route::post('/gastos', [VentaController::class, 'storeGasto'])->name('gastos.store');
    Route::delete('/gastos/{id}', [VentaController::class, 'destroyGasto'])->name('gastos.destroy');

    // --- ZONA DE VENTAS EXTERNAS ---
    Route::get('/ventas-externas/crear', [VentaController::class, 'createFuera'])->name('ventas_fuera.create');
    Route::post('/ventas-externas', [VentaController::class, 'storeFuera'])->name('ventas_fuera.store');
    Route::get('/ventas-externas/{id}/editar', [VentaController::class, 'editFuera'])->name('ventas_fuera.edit'); 
    Route::put('/ventas-externas/{id}', [VentaController::class, 'updateFuera'])->name('ventas_fuera.update'); 
    Route::delete('/ventas-externas/{id}', [VentaController::class, 'destroyFuera'])->name('ventas_fuera.destroy');

    // --- ZONA DE ALMACÉN / INVENTARIO ---
    Route::get('/inventario', [InventarioController::class, 'index'])->name('inventario.index');
    Route::post('/inventario/ingreso', [InventarioController::class, 'store'])->name('inventario.store');
    Route::post('/inventario/producto', [InventarioController::class, 'storeProducto'])->name('inventario.producto.store');

    // --- ZONA DE CLIENTES ---
    Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');
    Route::post('/clientes/importar', [ClienteController::class, 'import'])->name('clientes.import');
    
    // RUTA API PARA EL BUSCADOR DE CLIENTES POR RUC (VENTAS)
    Route::get('/api/buscar-cliente/{ruc}', [ClienteController::class, 'buscarPorRuc']);
    
    // Rutas para notificaciones
    Route::get('/notificaciones', [VentaController::class, 'getNotificaciones'])->name('notificaciones.index');
    Route::post('/notificaciones/{id}/leer', [VentaController::class, 'marcarAsRead'])->name('notificaciones.leer');
    
    // Ruta rápida para marcar una venta como cobrada/cancelada
    Route::post('/ventas/{id}/cancelar-credito', [VentaController::class, 'cancelarCredito'])->name('ventas.cancelar_credito');

    // --- RUTA DE EMERGENCIA PARA SERVIDOR (MIGRACIONES) ---
    Route::get('/migrar-base-de-datos-papi', function() {
        try {
            // Se usa fresh para limpiar cualquier residuo y estructurar desde cero de forma segura
            Artisan::call('migrate:fresh --seed --force');
            return '¡Base de datos creada y con seeders listos mano! Ya puedes volver al inicio.';
        } catch (\Exception $e) {
            return 'Error al migrar: ' . $e->getMessage();
        }
    });

}); // ✅ Cierre correcto del grupo de middleware 'auth'

require __DIR__.'/auth.php';