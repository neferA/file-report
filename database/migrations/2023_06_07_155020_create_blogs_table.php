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
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('tipo_garantia_id'); // Clave foránea
            $table->unsignedBigInteger('unidad_ejecutora_id'); // Clave foránea 

            $table->text('num_boleta');
            $table->text('proveedor');
            $table->text('motivo');
            $table->text('usuario');
            $table->enum('estado', ['liberado', 'ejecutado', 'renovado'])->default('liberado');
            $table->timestamps();
            
            $table->foreign('tipo_garantia_id')->references('id')->on('tipo_garantia')->onDelete('cascade');        
            $table->foreign('unidad_ejecutora_id')->references('id')->on('unidad_ejecutora')->onDelete('cascade');        

           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
