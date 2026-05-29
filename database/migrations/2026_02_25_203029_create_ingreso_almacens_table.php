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
    Schema::create('ingreso_almacens', function (Blueprint $table) {
        $table->id();
        
        // Relación con el producto que está ingresando
        $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade');
        
        // Cuántos sacos están entrando
        $table->decimal('cantidad', 10, 2);
        
        // Por qué entraron (Ej: Producción, Devolución, Ajuste)
        $table->string('motivo')->default('Producción'); 
        
        // Alguna observación opcional
        $table->text('detalle')->nullable(); 
        
        $table->date('fecha');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingreso_almacens');
    }
};
