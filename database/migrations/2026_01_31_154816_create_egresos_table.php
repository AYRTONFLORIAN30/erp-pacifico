<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('egresos', function (Blueprint $table) {
            $table->id();
            
            // 1. Datos del Documento
            $table->date('fecha_emision');
            $table->string('descripcion'); // Aquí guardarás lo que elijan del desplegable (Ej: "Combustible")
            $table->string('n_comprobante')->nullable(); // Puede estar vacío si no hay
            $table->string('guia')->nullable();
            $table->string('estado_guia')->nullable(); // "Archivado", "Pendiente", etc.
            
            // 2. Datos del Proveedor
            $table->string('ruc', 11); // Usamos string para asegurar los 11 dígitos
            $table->string('razon_social');
            
            // 3. Montos (Usamos Decimal para dinero, nunca Float)
            $table->decimal('no_gravado', 12, 2)->default(0); // 12 dígitos en total, 2 decimales
            $table->decimal('otras_tasas', 12, 4)->default(0); // Tasa de cambio puede tener más decimales
            
            // Cálculos (Base, IGV, Total)
            $table->decimal('base_imponible', 12, 2)->default(0);
            $table->decimal('igv', 12, 2)->default(0);
            $table->decimal('total', 12, 2); // Esta es la columna "Factura"
            
            // 4. Detracciones
            $table->decimal('detraccion_monto', 12, 2)->nullable(); // 10% del total
            $table->date('detraccion_fecha')->nullable(); // Mejor date que string para ordenar
            
            // 5. Estado y Responsable
            $table->string('nc_nd')->nullable(); // Nota de Crédito/Débito
            $table->text('glosa')->nullable();   // Texto largo para detalles
            
            // Saldo Actual: Manejamos estado y monto
            $table->string('estado_pago')->default('PENDIENTE'); // CANCELADO, ANULADO, PENDIENTE
            $table->decimal('saldo_pendiente', 12, 2)->default(0); // Si está cancelado será 0.00
            
            $table->string('responsable'); // Nombre de la persona (Ej: Patricia, Ricardo)

            $table->timestamps(); // Crea created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('egresos');
    }
};
