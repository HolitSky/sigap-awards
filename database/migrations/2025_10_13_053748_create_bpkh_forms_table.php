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
        Schema::create('bpkh_forms', function (Blueprint $table) {
            $table->id();
            $table->string('respondent_id')->unique();
            $table->string('nama_bpkh')->nullable();
            $table->string('petugas_bpkh')->nullable();
            $table->string('phone')->nullable();
            $table->string('website')->nullable();
            $table->enum('status_nilai', ['pending','in_review','scored'])->default('pending');
            $table->unsignedInteger('total_score')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedInteger('sheet_row_number')->nullable();
            $table->json('meta')->nullable();
            $table->timestamp('synced_at')->nullable();
            $table->timestamps();
            $table->index('status_nilai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bpkh_forms');
    }
};
