<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gasto extends Model
{
    use HasFactory;

    // Ya no forzamos la tabla egresos, Laravel buscará 'gastos' por defecto
    protected $fillable = ['fecha', 'detalle', 'monto'];

    protected $casts = [
        'fecha' => 'date',
    ];
}