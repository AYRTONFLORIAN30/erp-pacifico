<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    // Solo dejamos los datos generales del documento (Cabecera)
    protected $fillable = [
        'fecha',
        'almacen_origen',
        'tipo_comprobante',
        'numero_comprobante',
        'codigo_guia',
        'numero_guia',
        'cliente',
        'detalle',
        'vendedor',
        'zona',
        'lugar',
        'con_igv',
        'total_facturado',
        'forma_pago',
        'monto_contado',
        'monto_credito',
        'movimiento',

        // 👇 Nuevos campos para la Detracción (SPOT)
        'aplica_detraccion',
        'tipo_detraccion',
        'monto_detraccion',
        'fecha_pago_detraccion',
    ];

    protected $casts = [
        'fecha' => 'date',
        'con_igv' => 'boolean',
        
        // Mapeo automático de los tipos de datos
        'aplica_detraccion' => 'boolean',
        'fecha_pago_detraccion' => 'date',
    ];

    /**
     * RELACIÓN MAESTRO-DETALLE
     * Una Venta (Factura/Boleta) puede tener muchos productos adentro.
     */
    public function detalles()
    {
        return $this->hasMany(VentaDetalle::class, 'venta_id');
    }
}