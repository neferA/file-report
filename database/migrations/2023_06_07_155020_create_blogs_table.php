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

            $table->unsignedBigInteger('next_renewed_blog_id')->nullable();
            $table->unsignedBigInteger('renewed_blog_id')->nullable(); // Clave foránea hacia la versión renovada 
            $table->unsignedBigInteger('tipo_garantia_id')->nullable();// Clave foránea
            $table->unsignedBigInteger('unidad_ejecutora_id'); // Clave foránea 
            $table->unsignedBigInteger('afianzadora_id'); // Clave foránea
            // $table->unsignedBigInteger('id_boleta_real')->nullable();



            $table->text('num_boleta');
            $table->text('empresa');
            $table->text('motivo');
            $table->text('usuario');
            $table->enum('estado', ['vigente','liberado', 'ejecutado', 'renovado','vencido'])->default('vigente');
            $table->timestamps();
            
            $table->foreign('tipo_garantia_id')->references('id')->on('tipo_garantia')->onDelete('cascade');        
            $table->foreign('unidad_ejecutora_id')->references('id')->on('unidad_ejecutora')->onDelete('cascade');        
            $table->foreign('afianzadora_id')->references('id')->on('afianzadora')->onDelete('cascade');
            $table->foreign('renewed_blog_id')->references('id')->on('renewed_blogs')->onDelete('cascade')->onUpdate('cascade')->name('blogs_renewed_blog_id_foreign'); 
           
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
