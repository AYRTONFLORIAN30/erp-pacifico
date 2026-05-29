<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();

            // 1. DATOS DEL COMPROBANTE
            $table->date('fecha'); 
            $table->string('tipo_comprobante'); // Factura o Boleta
            $table->string('numero_comprobante'); // N° (# de la factura o boleta)
            
            // 2. DATOS DE GUÍA
            $table->string('codigo_guia')->nullable(); // código de guía 
            $table->string('numero_guia')->nullable(); // Numero de guía 

            // 3. DATOS DEL CLIENTE Y ZONA
            $table->string('cliente'); 
            $table->text('detalle')->nullable(); 
            $table->string('vendedor'); 
            $table->string('zona')->nullable(); 
            $table->string('lugar')->nullable(); 

            // 4. CÁLCULOS E IMPUESTOS
            // (Los campos de producto, cantidad y precio fueron movidos a venta_detalles)
            $table->boolean('con_igv')->default(false); // Check: Con IGV / Sin IGV
            $table->decimal('total_facturado', 12, 2); // El Total final
            
            // 5. PAGOS
            $table->string('forma_pago'); // Contado, Crédito
            $table->decimal('monto_contado', 12, 2)->default(0); 
            $table->decimal('monto_credito', 12, 2)->default(0); 
            $table->string('movimiento')->nullable(); // Depósito o Caja

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};