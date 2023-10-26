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
        Schema::create('renewed_blogs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_blog_id'); // Clave foránea hacia la boleta original
            $table->unsignedBigInteger('renewed_blog_id'); // Clave foránea hacia la boleta renovada
            // Otros campos necesarios para la boleta renovada
            // ...
            $table->timestamps();

            $table->foreign('parent_blog_id')->references('id')->on('blogs')->onDelete('cascade')->onUpdate('cascade')->name('renewed_blogs_parent_blog_id_foreign');
            $table->foreign('renewed_blog_id')->references('id')->on('blogs')->onDelete('cascade')->onUpdate('cascade')->name('renewed_blogs_renewed_blog_id_foreign');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('renewed_blogs');
    }
};