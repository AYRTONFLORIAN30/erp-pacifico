<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;

class ProductoCatalogoSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Limpiamos la tabla para borrar los 33 registros viejos/malos
        // Nota: Si usas claves foráneas, usamos DB::statement para evitar errores
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Producto::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Tu lista oficial de 25 productos
        $productos = [
            'ABONAZA PAPERO. SACOS DE 50KG. MARCA: PACIFICO.',
            'AGRO YESO (SULFATO DE CALCIO MOLIDO PARA EL USO AGRICOLA), SACOS DE 50KG. MARCA: PACIFICO.',
            'AGROCAL-MIX (CAL AGRICOLA SULFOCALCICA ) SACOS DE 25KG. HIDROXIDO DE CALCIO AL 35% APROX. MARCA: PACIFICO.',
            'AGROCAL-MIX (ENMIENDA AGRICOLA) SACOS DE 25KG. HIDROXIDO DE CALCIO AL 35% APROX. MARCA: PACIFICO.',
            'AGROCAL-MIX TROPICAL (ENMIENDA AGRICOLA) SACOS DE 25KG. HIDROXIDO DE CALCIO AL 35% APROX. MARCA: PACIFICO',
            'BIO-CAL SILICATO DE CALCIO 60% AL 65% + HIDROXIDO DE CALCIO 35% - 40%. SACOS DE 25KG. MARCA: M&DS.',
            'BIO-CAL SILICATO DE CALCIO 60% AL 65%+ HIDROXIDO DE CALCIO 35% - 40%.SACOS DE 25KG.M&DS',
            'CAL BORDALESA (USO DOMESTICO) HIDROXIDO DE CALCIO 90-98%. SACOS DE 25 KG. MARCA: PACIFICO.',
            'CAL NIEVE ANDINA – HIDROXIDO DE CALCIO AL 35%-40% APROX. SACOS DE 25 KG. MARCA M&DS',
            'CARBO CAL (CARBONATO DE CALCIO) PARA USO AGRICOLA. SACOS DE 50KG. MARCA PACIFICO.',
            'DIATOMITA PARA USO AGRICOLA. SACOS DE 25KG. MARCA: PACIFICO',
            'DOLOMITA AGRICOLA SACOS DE 50KG. MARCA: PACIFICO',
            'FILLER (HIDROXIDO DE CALCIO AL 20% APROX.) USO AGRICOLA SACOS DE 50KG. SIN MARCA.',
            'GALLINAZA (ABONO ORGANICO) SACOS DE 40KG. MARCA: PACIFICO.',
            'HIDROCAL (USO DOMESTICO) HIDROXIDO DE CALCIO PARA TRATAMIENTO DE AGUA 94-98%. SACOS DE 25 KG. MARCA: M&DS',
            'HUMUS DE LOMBRIZ, SACOS DE 50KG. MARCA: PACIFICO.',
            'NUTRI ABONAZA GALLINAZA COMPUESTA SACOS DE 50KG. MARCA: PACIFICO.',
            'OXIDO DE CALCIO DE 35%-40% APROX. SACOS DE 50KG. APROX. SIN MARCA.',
            'ROCA FOSFORICA BAYOVAR SACOS DE 50KG. MARCA: PACIFICO',
            'ROCA FOSFORICA BAYOVAR SACOS DE 50KG. SIN MARCA.',
            'SULFATO DE CALCIO GRANULADO.  (USO AGRICOLA), SACOS DE 50KG. MARCA: PACIFICO',
            'SULFATO DE CALCIO GRANULADO.  (USO AGRICOLA), SACOS DE 50KG. SIN MARCA',
            'SUPER MAGNO - CAL-MIX, SACOS DE 50KG. MARCA: PACIFICO.',
            'ULEX-30(ULEXITA AGRICOLA), SACOS DE 25KG. MARCA: PACIFICO.',
            'YESO MOLIDO NATURAL (USO AGRICOLA) SACOS DE 50KG. APROX. SIN MARCA'
        ];

        // 3. Ordenamos alfabéticamente por si acaso
        sort($productos);

        // 4. Los insertamos todos en la base de datos con stock 0 inicial
        foreach ($productos as $producto) {
            Producto::create([
                'nombre' => $producto,
                'stock' => 0,
                // Si tu tabla tiene columnas extra (como precio_base o detraccion), ponlas aquí con valores por defecto
            ]);
        }
    }
}