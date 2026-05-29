<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ventas_externas', function (Blueprint $table) {
            $table->string('forma_pago')->default('Contado')->after('total_facturado');
            $table->decimal('monto_contado', 10, 2)->default(0)->after('forma_pago');
            $table->decimal('monto_credito', 10, 2)->default(0)->after('monto_contado');
            $table->string('movimiento')->default('CAJA')->after('monto_credito');
        });
    }

    public function down(): void
    {
        Schema::table('ventas_externas', function (Blueprint $table) {
            $table->dropColumn(['forma_pago', 'monto_contado', 'monto_credito', 'movimiento']);
        });
    }
};