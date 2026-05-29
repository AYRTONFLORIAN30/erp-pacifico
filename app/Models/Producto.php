<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    // Le damos permiso a Laravel para guardar datos masivamente en estas columnas
    protected $fillable = [
        'nombre',
        'detraccion',
        'precio_base',
        'stock',            // Mantenemos este como el "Total Global" sumado
        'stock_tarma',      // <-- NUEVO
        'stock_lima',       // <-- NUEVO
        'stock_lambayeque', // <-- NUEVO
    ];
}