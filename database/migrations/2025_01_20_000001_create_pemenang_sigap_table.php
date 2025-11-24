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
        Schema::create('pemenang_sigap', function (Blueprint $table) {
            $table->id();
            $table->enum('kategori', [
                'poster_terbaik',
                'poster_favorit',
                'pengelola_igt_terbaik',
                'inovasi_bpkh_terbaik',
                'inovasi_produsen_terbaik'
            ])->comment('Kategori pemenang');
            $table->enum('tipe_peserta', ['bpkh', 'produsen'])->comment('BPKH atau Produsen');
            $table->string('nama_pemenang')->comment('Nama BPKH atau Produsen pemenang');
            $table->string('nama_petugas')->nullable()->comment('Nama petugas (khusus untuk Pengelola IGT Terbaik)');
            $table->enum('juara', ['juara_1', 'juara_2', 'juara_3', 'juara_harapan'])->comment('Peringkat juara');
            $table->text('deskripsi')->nullable()->comment('Deskripsi singkat');
            $table->string('foto_path')->nullable()->comment('Path foto pemenang');
            $table->integer('urutan')->default(0)->comment('Urutan tampil');
            $table->boolean('is_active')->default(true)->comment('Status aktif');
            $table->timestamps();

            // Index untuk performa
            $table->index(['kategori', 'tipe_peserta']);
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemenang_sigap');
    }
};
