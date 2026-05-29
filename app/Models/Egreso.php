<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Egreso extends Model
{
    use HasFactory;

    protected $table = 'egresos';

    protected $fillable = [
        'fecha_emision',   //fecha que fue emita el comprobante de pago 
        'descripcion',     //descripcion de la venta
        'n_comprobante',  //# de comprobante
        'guia',          //tipo de guia emitida
        'estado_guia',   //estado de la guia de la empresa
        'ruc',           //# de RUC de la empresa 
        'razon_social',  //Razon social de la empresa 
        'no_gravado',       // Guardará el monto total si es "No Gravado"
        'otras_tasas',      // Guardará el valor del cambio si aplica (o 0)
        'base_imponible',   //impuesto a pagar
        'igv',              //igv de la empresa
        'total',            //total de la factura/boleta
        'detraccion_monto', //monto de la detraccion
        'detraccion_fecha', //fecha de la detraccion
        'detraccion_porcentaje', // (Opcional) Para saber qué % se usó
        'nc_nd',            //nota de credito o nota de debito
        'glosa',            //glosario o detalle de la venta
        'estado_pago',      //estado del pago 
        'metodo_pago',      // 👈 ¡AQUÍ ESTÁ EL CAMPO NUEVO AGREGADO!
        'saldo_pendiente',  //saldo pendiente o a pagar de la empresa
        'responsable',      //responsable de la venta
    ];
}

