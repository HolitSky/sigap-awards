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
        Schema::create('presentation_sessions_config', function (Blueprint $table) {
            $table->id();
            $table->string('session_name'); // e.g., "Sesi 1", "Sesi 2", etc.
            $table->integer('session_number'); // 1, 2, 3, 4, 5, etc.
            $table->enum('session_type', ['bpkh', 'produsen']); // Type of session
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0); // Display order
            $table->timestamps();
            
            $table->unique(['session_number', 'session_type']); // Prevent duplicate session numbers per type
            $table->index('session_type');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presentation_sessions_config');
    }
};
