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

            $table->unsignedBigInteger('assets_waranty_id')->nullable(); 

            $table->text('num_boleta');
            $table->text('proveedor');
            $table->text('motivo');
            $table->text('ejecutora');
            $table->text('usuario');
            $table->enum('estado', ['liberado', 'ejecutado', 'renovado'])->default('liberado');
            $table->timestamps();
            
            $table->foreign('assets_waranty_id')->references('id')->on('assets_waranty')->onDelete('cascade');

           
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
