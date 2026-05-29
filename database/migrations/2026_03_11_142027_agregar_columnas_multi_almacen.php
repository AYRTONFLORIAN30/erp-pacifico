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
        // 1. Agregamos los 3 almacenes a la tabla de PRODUCTOS
        Schema::table('productos', function (Blueprint $table) {
            $table->decimal('stock_tarma', 10, 2)->default(0)->after('stock');
            $table->decimal('stock_lima', 10, 2)->default(0)->after('stock_tarma');
            $table->decimal('stock_lambayeque', 10, 2)->default(0)->after('stock_lima');
        });

        // 2. Agregamos el destino a la tabla de INGRESOS (Historial)
        Schema::table('ingreso_almacens', function (Blueprint $table) {
            $table->string('almacen_destino')->default('Tarma')->after('motivo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Esto es por si algún día quieres deshacer los cambios
        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn(['stock_tarma', 'stock_lima', 'stock_lambayeque']);
        });

        Schema::table('ingreso_almacens', function (Blueprint $table) {
            $table->dropColumn('almacen_destino');
        });
    }
};    