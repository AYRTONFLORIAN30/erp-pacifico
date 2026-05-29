<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\Venta;
use App\Models\VentaExterna;
use App\Models\User;
use App\Notifications\VentaVencidaNotification;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

// Comando por defecto de Laravel
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// NUEVO: Comando automatizado para Abonos y Enmiendas Pacífico
Artisan::command('creditos:verificar', function () {
    $hoy = now()->toDateString();
    
    // Obtener los usuarios que deben enterarse de las deudas
    $usuariosANotificar = User::whereIn('rol', ['admin', 'ventas'])->get();

    // 1. Validar Ventas Internas vencidas que no se han pagado
    $internasVencidas = Venta::where('cancelado', false)
        ->where('fecha_vencimiento', '<=', $hoy)
        ->get();

    // 2. Validar Ventas Externas vencidas que no se han pagado
    $externasVencidas = VentaExterna::where('cancelado', false)
        ->where('fecha_vencimiento', '<=', $hoy)
        ->get();

    // Procesar y enviar alertas evitando duplicar la misma notificación en la BD
    foreach ($usuariosANotificar as $user) {
        
        // Notificar deudas internas
        foreach ($internasVencidas as $v) {
            $existe = $user->notifications()
                ->where('data->venta_id', $v->id)
                ->where('data->tipo_venta', 'Interna')
                ->exists();
                
            if (!$existe) {
                $user->notify(new VentaVencidaNotification($v->id, $v->cliente, $v->total_facturado, 'Interna'));
            }
        }

        // Notificar deudas externas
        foreach ($externasVencidas as $ve) {
            $existe = $user->notifications()
                ->where('data->venta_id', $ve->id)
                ->where('data->tipo_venta', 'Externa')
                ->exists();
                
            if (!$existe) {
                $user->notify(new VentaVencidaNotification($ve->id, $ve->cliente, $ve->total_facturado, 'Externa'));
            }
        }
    }

    $this->info('Control de cuentas vencidas ejecutado correctamente.');
})->purpose('Revisa ventas internas y externas con créditos vencidos')->everyMinute();

