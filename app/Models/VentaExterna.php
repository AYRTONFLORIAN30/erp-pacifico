<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VentaExterna extends Model
{
    use HasFactory;

    protected $table = 'ventas_externas';

    protected $guarded = []; 

    protected $casts = [
        'fecha' => 'date',
        'con_igv' => 'boolean',
    ];
 

}