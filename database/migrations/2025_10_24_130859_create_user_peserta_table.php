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
        Schema::create('user_peserta', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone')->nullable(); // Nomor WhatsApp
            $table->string('foto')->nullable(); // Path foto peserta
            $table->enum('kategori', ['bpkh', 'produsen']); // Kategori peserta
            $table->unsignedBigInteger('bpkh_id')->nullable(); // FK ke tabel bpkh_list
            $table->unsignedBigInteger('produsen_id')->nullable(); // FK ke tabel produsen_list
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->rememberToken();
            $table->timestamps();

            // Foreign keys
            $table->foreign('bpkh_id')->references('id')->on('bpkh_list')->onDelete('set null');
            $table->foreign('produsen_id')->references('id')->on('produsen_list')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_peserta');
    }
};
