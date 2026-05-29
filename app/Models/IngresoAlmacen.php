<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IngresoAlmacen extends Model
{
    use HasFactory;

    protected $fillable = [
        'producto_id',
        'cantidad',
        'motivo',
        'detalle',
        'fecha',
        'almacen_destino' // <-- NUEVO: Para saber a dónde ingresó
    ];

    // Relación: Este ingreso pertenece a un producto específico de tu almacén
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}
