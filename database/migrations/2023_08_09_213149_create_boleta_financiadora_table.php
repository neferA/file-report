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
        Schema::create('boleta_financiadora', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('blogs_id'); // Clave foránea para la relación con 'blogs'
            $table->unsignedBigInteger('financiadora_id'); // Clave foránea para la relación con 'financiadora'

            // Agrega más columnas si es necesario
            $table->timestamps();

            $table->foreign('blogs_id')->references('id')->on('blogs')->onDelete('cascade'); // Define la relación
            $table->foreign('financiadora_id')->references('id')->on('financiadora')->onDelete('cascade'); // Define la relación

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('boleta_financiadora');
    }
};
