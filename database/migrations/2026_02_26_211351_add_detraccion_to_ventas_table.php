<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            // Agregamos las 4 columnas de la cajita amarilla
            if (!Schema::hasColumn('ventas', 'aplica_detraccion')) {
                $table->boolean('aplica_detraccion')->default(0)->after('total_facturado');
                $table->decimal('tipo_detraccion', 5, 2)->nullable()->after('aplica_detraccion');
                $table->decimal('monto_detraccion', 10, 2)->nullable()->after('tipo_detraccion');
                $table->date('fecha_pago_detraccion')->nullable()->after('monto_detraccion');
            }
        });
    }

    public function down(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->dropColumn(['aplica_detraccion', 'tipo_detraccion', 'monto_detraccion', 'fecha_pago_detraccion']);
        });
    }
};