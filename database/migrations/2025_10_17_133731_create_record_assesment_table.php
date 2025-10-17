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
        Schema::create('record_assesment', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('user_name');
            $table->string('user_email');
            $table->string('user_role');
            $table->string('form_type'); // 'bpkh' or 'produsen'
            $table->string('respondent_id');
            $table->string('form_name'); // nama_bpkh or nama_instansi
            $table->string('action_type')->default('scoring'); // scoring, review, update
            $table->integer('total_score')->nullable(); // Nilai final keseluruhan
            $table->json('meta_changes')->nullable(); // Jawaban soal yang di-edit
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index('user_id');
            $table->index('form_type');
            $table->index('respondent_id');
            $table->index(['form_type', 'respondent_id']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('record_assesment');
    }
};
