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
        Schema::create('favorite_poster_votes', function (Blueprint $table) {
            $table->id();
            $table->string('respondent_id');
            $table->string('participant_name');
            $table->string('participant_type'); // 'bpkh' or 'produsen'
            $table->string('petugas')->nullable();
            $table->integer('vote_count')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('respondent_id');
            $table->index('participant_type');
            $table->unique(['respondent_id', 'participant_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favorite_poster_votes');
    }
};
