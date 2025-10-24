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
        Schema::create('bpkh_list', function (Blueprint $table) {
            $table->id();
            $table->string('nama_wilayah');
            $table->string('kode_wilayah')->nullable(); // Contoh: I, II, III, dst
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bpkh_list');
    }
};
