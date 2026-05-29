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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            
            // 1. Identificación
            $table->string('name'); // Nombre completo
            $table->string('username')->unique()->nullable(); // Usuario (Para login)
            
            // 2. Credenciales
            $table->string('email')->unique(); 
            // OJO: Si en el futuro quieres usar "Olvidé mi contraseña", Laravel pedirá email_verified_at
            // Por ahora lo dejamos sin eso como tú lo tenías.
            $table->string('password'); 
            
            // 3. Roles y Estado
            // CORRECCIÓN: Cambiamos 'enum' por 'string' para que acepte "Administrador"
            $table->string('rol')->default('encargado'); 
            
            $table->boolean('estado')->default(true); // Activo/Inactivo
            
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};  
