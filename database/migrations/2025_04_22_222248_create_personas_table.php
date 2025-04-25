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
        Schema::create('personas', function (Blueprint $table) {
            $table->id('id_persona');
            $table->string('nombre', 50);
            $table->string('apellido', 50);
            $table->string('numero_identidad', 15)->unique();
            $table->string('telefono', 15)->nullable();
            $table->text('direccion')->nullable();
            $table->string('ruta_foto')->nullable();
            $table->enum('tipo', ['Socio', 'Cliente']);
            $table->boolean('estado')->default(true);
    
            // Auditoría
            $table->foreignId('creado_por')->nullable()->constrained('users', 'id')->nullOnDelete();
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->foreignId('actualizado_por')->nullable()->constrained('users', 'id')->nullOnDelete();
            $table->timestamp('fecha_actualizacion')->nullable()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personas');
    }
};
