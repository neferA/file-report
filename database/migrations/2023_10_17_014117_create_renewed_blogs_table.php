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
            // Otros campos necesarios para la boleta renovada
            // ...
            $table->timestamps();

            $table->foreign('parent_blog_id')->references('id')->on('blogs')->onDelete('cascade');
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