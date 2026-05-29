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
    Schema::create('productos', function (Blueprint $table) {
        $table->id();
        $table->string('codigo')->nullable();
        $table->string('nombre'); // Ej: Agrocal - Mix, Cal Bordalesa
        $table->decimal('peso_kg', 8, 2)->nullable(); // Peso por saco
        $table->decimal('precio_base', 10, 2)->nullable();
        $table->decimal('stock', 10, 2)->default(0); // El inventario actual
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
