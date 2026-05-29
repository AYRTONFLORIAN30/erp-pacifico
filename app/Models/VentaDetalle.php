<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VentaDetalle extends Model
{
    use HasFactory;

    // Aseguramos que apunte a la tabla correcta (opcional, pero buena práctica)
    protected $table = 'venta_detalles';

    // Los campos que el sistema tiene permiso para llenar al crear el registro
    protected $fillable = [
        'venta_id',
        'producto_id',
        'cantidad',
        'tonelada',
        'precio_unitario',
        'subtotal',
    ];

    /**
     * RELACIONES INVERSAS
     */

    // Este detalle pertenece a una Venta (Factura/Boleta)
    public function venta()
    {
        return $this->belongsTo(Venta::class, 'venta_id');
    }

    // Este detalle pertenece a un Producto de tu almacén
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}

// un eterno suspirar wooo woooahh lucas pizzeck abueloski marco reus matts humels  siete mirados me tienen al frente aqui viene el jefe el que cuenta los verdes 