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
        Schema::create('input_produsen_posters', function (Blueprint $table) {
            $table->id();
            $table->string('nama_instansi');
            $table->string('poster_pdf_path');
            $table->string('original_filename')->nullable();
            $table->string('original_mime')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->timestamps();

            $table->index('nama_instansi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('input_produsen_posters');
    }
};
