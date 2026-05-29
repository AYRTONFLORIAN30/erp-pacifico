<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class VentaVencidaNotification extends Notification
{
    use Queueable;

    protected $idVenta;
    protected $cliente;
    protected $total;
    protected $tipo;

    // Recibe los datos compactos desde el routes/console.php
    public function __construct($idVenta, $cliente, $total, $tipo = 'Interna')
    {
        $this->idVenta = $idVenta;
        $this->cliente = $cliente;
        $this->total = $total;
        $this->tipo = $tipo;
    }

    // Indica que se guardará en la tabla 'notifications' de la Base de Datos
    public function via($notifiable)
    {
        return ['database'];
    }

    // Estructura los datos JSON que consume la campana en el navigation.blade.php
    public function toArray($notifiable)
    {
        return [
            'venta_id' => $this->idVenta,
            'tipo_venta' => $this->tipo,
            'cliente' => $this->cliente,
            'total' => $this->total,
            'mensaje' => "⚠️ Venta {$this->tipo} #{$this->idVenta} de S/. {$this->total} ({$this->cliente}) venció y sigue pendiente.",
        ];
    }
}