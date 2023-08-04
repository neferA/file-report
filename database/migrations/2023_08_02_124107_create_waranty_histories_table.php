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
        Schema::create('waranty_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('blogs_id');
            $table->text('titulo');
            $table->text('contenido');
            $table->text('caracteristicas');
            $table->text('observaciones');
            $table->date('fecha_inicio');
            $table->date('fecha_final');


            $table->timestamps();

            $table->foreign('blogs_id')->references('id')->on('blogs')->onDelete('cascade');
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('waranty_histories');
    }
};
