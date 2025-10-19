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
        Schema::create('record_presentasi_assesment', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('user_name');
            $table->string('user_email');
            $table->string('user_role');
            $table->enum('form_type', ['bpkh', 'produsen']);
            $table->string('respondent_id');
            $table->string('form_name');
            $table->string('action_type')->default('presentation_assessment');
            $table->decimal('nilai_akhir_user', 5, 2)->nullable();
            $table->text('catatan_juri')->nullable();
            $table->string('rekomendasi')->nullable();
            $table->json('aspek_scores')->nullable()->comment('Detailed aspect scores for this assessment');
            $table->timestamps();
            
            $table->index(['form_type', 'respondent_id']);
            $table->index('user_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('record_presentasi_assesment');
    }
};
