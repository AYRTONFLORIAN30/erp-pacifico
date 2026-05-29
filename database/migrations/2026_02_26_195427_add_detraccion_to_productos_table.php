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
        Schema::table('productos', function (Blueprint $table) {
            // Agregamos la columna 'detraccion' con 2 decimales (ej: 1.50, 10.00)
            // default(0) asegura que los productos que ya tienes no den error
            $table->decimal('detraccion', 5, 2)->default(0)->after('nombre');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            // Eliminamos la columna por si alguna vez necesitas revertir la migración
            $table->dropColumn('detraccion');
        });
    }
};