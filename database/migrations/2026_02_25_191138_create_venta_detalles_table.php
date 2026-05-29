<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('venta_detalles', function (Blueprint $table) {
        $table->id();
        // Relaciones con las otras tablas
        $table->foreignId('venta_id')->constrained('ventas')->onDelete('cascade');
        $table->foreignId('producto_id')->constrained('productos');
        
        // Detalle de lo vendido
        $table->decimal('cantidad', 10, 2); // Cantidad de sacos
        $table->decimal('tonelada', 10, 2)->nullable(); 
        $table->decimal('precio_unitario', 10, 2);
        $table->decimal('subtotal', 10, 2); // cantidad * precio_unitario
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('venta_detalles');
    }
};
