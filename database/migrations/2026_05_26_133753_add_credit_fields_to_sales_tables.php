<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Control para la tabla de ventas internas
        Schema::table('ventas', function (Blueprint $table) {
            if (!Schema::hasColumn('ventas', 'plazo_dias')) {
                $table->integer('plazo_dias')->nullable()->after('forma_pago');
            }
            if (!Schema::hasColumn('ventas', 'fecha_vencimiento')) {
                $table->date('fecha_vencimiento')->nullable()->after('plazo_dias');
            }
            if (!Schema::hasColumn('ventas', 'cancelado')) {
                $table->boolean('cancelado')->default(true)->after('fecha_vencimiento');
            }
        });

        // 2. Control para la tabla de ventas externas (Evita el error de duplicidad)
        Schema::table('ventas_externas', function (Blueprint $table) {
            if (!Schema::hasColumn('ventas_externas', 'plazo_dias')) {
                $table->integer('plazo_dias')->nullable()->after('forma_pago');
            }
            if (!Schema::hasColumn('ventas_externas', 'fecha_vencimiento')) {
                $table->date('fecha_vencimiento')->nullable()->after('plazo_dias');
            }
            if (!Schema::hasColumn('ventas_externas', 'cancelado')) {
                $table->boolean('cancelado')->default(true)->after('fecha_vencimiento');
            }
        });
    }

    public function down(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->dropColumn(['plazo_dias', 'fecha_vencimiento', 'cancelado']);
        });

        Schema::table('ventas_externas', function (Blueprint $table) {
            // Solo eliminamos si la estructura general se revierte
            $table->dropColumn(['plazo_dias', 'fecha_vencimiento', 'cancelado']);
        });
    }
};