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
        Schema::create('record_exhibition_assesments', function (Blueprint $table) {
            $table->id();
            $table->string('exhibition_type'); // 'bpkh' or 'produsen'
            $table->unsignedBigInteger('exhibition_id');
            $table->unsignedBigInteger('user_id');
            $table->string('user_name');
            $table->decimal('nilai_akhir_user', 8, 2);
            $table->text('catatan_juri')->nullable();
            $table->string('rekomendasi')->nullable();
            $table->timestamp('assessed_at');
            $table->timestamps();
            
            // Indexes
            $table->index(['exhibition_type', 'exhibition_id']);
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('record_exhibition_assesments');
    }
};
