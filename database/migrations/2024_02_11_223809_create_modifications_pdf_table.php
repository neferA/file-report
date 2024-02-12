<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('modifications_pdf', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('blogs_id');
            $table->string('boleta_pdf_path')->nullable(); // Nueva columna para la ruta de boletas
            $table->string('nota_pdf_path')->nullable(); // Nueva columna para la ruta de notas
            $table->timestamps();
        
            // Definir la clave foránea
            $table->foreign('blogs_id')->references('id')->on('blogs')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modifications_pdf');
    }
};
