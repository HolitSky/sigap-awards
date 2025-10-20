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
        Schema::create('sesi_bpkh_presentasi', function (Blueprint $table) {
            $table->id();
            $table->string('session_name'); // Sesi 1, Sesi 3, Sesi 5
            $table->string('respondent_id'); // ID dari bpkh_presentation_assesment
            $table->string('nama_bpkh'); // Nama BPKH
            $table->integer('order')->default(0); // Urutan dalam sesi
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Index untuk performa
            $table->index('session_name');
            $table->index('respondent_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sesi_bpkh_presentasi');
    }
};
