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
        Schema::create('produsen_exhibitions', function (Blueprint $table) {
            $table->id();
            $table->string('respondent_id')->nullable();
            $table->string('nama_instansi');
            $table->string('nama_petugas')->nullable();
            
            // Aspek penilaian (JSON) - contains scores from all juri
            $table->json('aspek_penilaian')->nullable();
            
            // Penilaian per juri (JSON)
            $table->json('penilaian_per_juri')->nullable();
            
            // Bobot exhibition
            $table->decimal('bobot_exhibition', 5, 2)->default(20.00);
            
            // Nilai final
            $table->decimal('nilai_final', 8, 2)->nullable();
            $table->decimal('nilai_final_dengan_bobot', 8, 2)->nullable();
            
            // Kategori otomatis
            $table->string('kategori_penilaian')->nullable();
            $table->text('deskripsi_kategori')->nullable();
            
            // Metadata
            $table->integer('total_juri_menilai')->default(0);
            $table->string('status')->default('pending'); // pending, assessed
            
            $table->timestamps();
            
            // Indexes
            $table->index('respondent_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produsen_exhibitions');
    }
};
