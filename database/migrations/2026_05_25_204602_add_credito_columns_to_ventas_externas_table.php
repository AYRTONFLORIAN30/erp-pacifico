<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('ventas_externas', function (Blueprint $table) {
            $table->integer('plazo_credito')->nullable()->after('forma_pago');
            $table->date('fecha_vencimiento')->nullable()->after('plazo_credito');
            $table->boolean('pagado')->default(false)->after('fecha_vencimiento');
        });
    }

    public function down()
    {
        Schema::table('ventas_externas', function (Blueprint $table) {
            $table->dropColumn(['plazo_credito', 'fecha_vencimiento', 'pagado']);
        });
    }
};