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
        Schema::create('produsen_presentasi_assesment', function (Blueprint $table) {
            $table->id();
            $table->string('respondent_id')->unique();
            $table->string('nama_instansi')->nullable();
            $table->string('nama_petugas')->nullable();
            
            // Aspek penilaian (stored as JSON metadata)
            $table->json('aspek_penilaian')->nullable()->comment('JSON: substansi_capaian, implementasi_strategi, kedalaman_analisis, kejelasan_alur, kemampuan_menjawab, kreativitas_daya_tarik');
            
            // Nilai per user/juri
            $table->json('penilaian_per_juri')->nullable()->comment('Array of {user_id, user_name, nilai_akhir_user, catatan, rekomendasi, assessed_at}');
            
            // Nilai final (average dari semua juri)
            $table->decimal('nilai_final', 5, 2)->nullable()->comment('Average dari semua penilaian juri');
            
            // Bobot presentasi
            $table->decimal('bobot_presentasi', 5, 2)->default(35)->comment('Default 35%');
            
            // Nilai final dengan bobot
            $table->decimal('nilai_final_dengan_bobot', 5, 2)->nullable()->comment('nilai_final * bobot_presentasi / 100');
            
            // Keterangan skor otomatis
            $table->string('kategori_skor')->nullable()->comment('Sangat Baik, Baik, Cukup, Kurang, Sangat Kurang');
            $table->text('deskripsi_skor')->nullable();
            
            // Metadata
            $table->unsignedBigInteger('synced_from_form_id')->nullable();
            $table->timestamp('synced_at')->nullable();
            $table->timestamps();
            
            $table->index('respondent_id');
            $table->index('kategori_skor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produsen_presentasi_assesment');
    }
};
