<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    /**
     * Los atributos que se pueden asignar de forma masiva.
     * Definimos estos campos basándonos en las columnas del Excel de clientes.
     */
    protected $fillable = [
        'ruc',           // RUC del cliente
        'razon_social',  // ID del cliente / Nombre de la empresa
        'lugar',         // Ciudad o distrito
        'direccion',     // Dirección física
        'telefono',      // Número de contacto
        'contacto',       // Persona de contacto
        'email',         // Dirección de correo electrónico
    ];
}