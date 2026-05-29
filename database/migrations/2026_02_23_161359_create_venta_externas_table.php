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
    Schema::create('ventas_externas', function (Blueprint $table) {
        $table->id();
        $table->date('fecha');
        $table->string('tipo_comprobante'); // Factura, Boleta, etc.
        $table->string('numero_comprobante');
        $table->string('codigo_guia')->nullable();
        $table->string('numero_guia')->nullable();
        $table->string('cliente');
        $table->string('detalle')->nullable();
        $table->string('vendedor');
        $table->string('zona');
        $table->string('lugar');
        $table->string('producto');
        $table->decimal('cantidad', 10, 2);
        $table->decimal('tonelada', 10, 2)->nullable();
        $table->decimal('precio_unitario', 10, 2);
        $table->boolean('con_igv')->default(false);
        $table->decimal('total_facturado', 10, 2);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('venta_externas');
    }
};
