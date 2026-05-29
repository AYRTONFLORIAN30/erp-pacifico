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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            
            // 1. Datos Personales
            $table->string('nombres');
            $table->string('apellidos');
            $table->string('dni', 8)->unique(); // DNI único de 8 dígitos
            $table->string('telefono')->nullable(); // Opcional, por si necesitan llamarlo
            
            // 2. Datos para la Boleta (CRÍTICO)
            $table->string('cargo'); // Ej: "Estibador", "Operario de Molino"
            $table->decimal('sueldo_basico', 10, 2)->default(0); // El sueldo base (mensual o diario según configures)
            $table->date('fecha_ingreso')->nullable(); // Para calcular liquidaciones o vacaciones
            
            // 3. Estado
            $table->boolean('activo')->default(true); // true = Trabajando, false = Despedido/Renunció
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
