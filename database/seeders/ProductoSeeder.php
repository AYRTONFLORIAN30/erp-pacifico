<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;

class ProductoSeeder extends Seeder
{
    public function run()
    {
        // --- GRUPO 1: DETRACCIÓN 1.5% (035) ---
        $productos_1_5 = [
            ['nombre' => 'ROCA FOSFORICA BAYOVAR SACOS DE 50KG. MARCA: PACIFICO', 'precio_base' => 32.00],
            ['nombre' => 'ROCA FOSFORICA BAYOVAR SACOS DE 50KG. SIN MARCA.', 'precio_base' => 32.00],
            ['nombre' => 'GALLINAZA (ABONO ORGANICO) SACOS DE 40KG. MARCA: PACIFICO.', 'precio_base' => 24.00],
            ['nombre' => 'HUMUS DE LOMBRIZ, SACOS DE 50KG. MARCA: PACIFICO.', 'precio_base' => 0.00],
            ['nombre' => 'ABONAZA PAPERO. SACOS DE 50KG. MARCA: PACIFICO.', 'precio_base' => 32.50],
            ['nombre' => 'NUTRI ABONAZA GALLINAZA COMPUESTA SACOS DE 50KG. MARCA: PACIFICO.', 'precio_base' => 0.00],
        ];

        foreach ($productos_1_5 as $prod) {
            Producto::firstOrCreate(
                ['nombre' => $prod['nombre']],
                ['detraccion' => 1.50, 'stock' => 0, 'precio_base' => $prod['precio_base']]
            );
        }

        // --- GRUPO 2: SIN DETRACCIÓN (0%) ---
        $productos_0 = [
            ['nombre' => 'AGROCAL-MIX TROPICAL (ENMIENDA AGRICOLA) SACOS 25KG. MARCA: PACIFICO', 'precio_base' => 18.00],
            ['nombre' => 'AGROCAL-MIX (ENMIENDA AGRICOLA) SACOS DE 25KG. MARCA: PACIFICO.', 'precio_base' => 18.00],
            ['nombre' => 'AGROCAL-MIX (CAL AGRICOLA SULFOCALCICA) SACOS DE 25KG. MARCA: PACIFICO.', 'precio_base' => 18.00],
        ];

        foreach ($productos_0 as $prod) {
            Producto::firstOrCreate(
                ['nombre' => $prod['nombre']],
                ['detraccion' => 0.00, 'stock' => 0, 'precio_base' => $prod['precio_base']]
            );
        }

        // --- GRUPO 3: DETRACCIÓN 10% (039) ---
        $productos_10 = [
            ['nombre' => 'SUPER MAGNO - CAL-MIX, SACOS DE 50KG. MARCA: PACIFICO.', 'precio_base' => 26.00],
            ['nombre' => 'DOLOMITA AGRICOLA SACOS DE 50KG. MARCA: PACIFICO', 'precio_base' => 0.00],
            ['nombre' => 'OXIDO DE CALCIO DE 35%-40% APROX. SACOS DE 50KG. SIN MARCA.', 'precio_base' => 0.00],
            ['nombre' => 'CAL BORDALESA (USO DOMESTICO) 90-98%. SACOS DE 25 KG. MARCA: PACIFICO.', 'precio_base' => 35.00],
            ['nombre' => 'HIDROCAL (USO DOMESTICO) 94-98%. SACOS DE 25 KG. MARCA: M&DS', 'precio_base' => 0.00],
            ['nombre' => 'AGRO YESO (SULFATO DE CALCIO MOLIDO), SACOS DE 50KG. MARCA: PACIFICO.', 'precio_base' => 22.00],
            ['nombre' => 'FILLER (HIDROXIDO DE CALCIO AL 20% APROX.) SACOS DE 50KG. SIN MARCA.', 'precio_base' => 0.00],
            ['nombre' => 'BIO-CAL SILICATO DE CALCIO 60% AL 65%. SACOS DE 25KG. MARCA: M&DS.', 'precio_base' => 0.00],
            ['nombre' => 'ULEX-30(ULEXITA AGRICOLA), SACOS DE 25KG. MARCA: PACIFICO.', 'precio_base' => 0.00],
            ['nombre' => 'CAL NIEVE ANDINA – HIDROXIDO DE CALCIO AL 35%-40%. SACOS 25 KG. M&DS', 'precio_base' => 0.00],
            ['nombre' => 'SULFATO DE CALCIO GRANULADO. SACOS DE 50KG. MARCA: PACIFICO', 'precio_base' => 53.00],
            ['nombre' => 'SULFATO DE CALCIO GRANULADO. SACOS DE 50KG. SIN MARCA', 'precio_base' => 53.00],
            ['nombre' => 'DIATOMITA PARA USO AGRICOLA. SACOS DE 25KG. MARCA: PACIFICO', 'precio_base' => 0.00],
            ['nombre' => 'CARBO CAL (CARBONATO DE CALCIO). SACOS DE 50KG. MARCA PACIFICO.', 'precio_base' => 0.00],
            ['nombre' => 'YESO MOLIDO NATURAL. SACOS DE 50KG. APROX. SIN MARCA', 'precio_base' => 0.00],
            ['nombre' => 'BIO-CAL SILICATO DE CALCIO 60% AL 65%+. SACOS DE 25KG.M&DS', 'precio_base' => 0.00],
        ];

        foreach ($productos_10 as $prod) {
            Producto::firstOrCreate(
                ['nombre' => $prod['nombre']],
                ['detraccion' => 10.00, 'stock' => 0, 'precio_base' => $prod['precio_base']]
            );
        }
    }
}