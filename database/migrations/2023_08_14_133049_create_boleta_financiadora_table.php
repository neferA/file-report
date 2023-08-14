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
        Schema::create('boleta_financiadora', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('blogs_id');
            $table->unsignedBigInteger('financiadora_id');
            // Agrega más columnas si es necesario
            $table->timestamps();

            $table->foreign('blogs_id')->references('id')->on('blogs')->onDelete('cascade');
            $table->foreign('financiadora_id')->references('id')->on('financiadoras')->onDelete('cascade');
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
