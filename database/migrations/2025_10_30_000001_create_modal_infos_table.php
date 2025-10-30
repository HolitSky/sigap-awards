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
        Schema::create('modal_infos', function (Blueprint $table) {
            $table->id();
            $table->string('title', 100); // h1 - max 100 char
            $table->string('subtitle', 200)->nullable(); // h3 - max 200 char (nullable for welcome modal)
            $table->boolean('is_show')->default(false); // Show/hide modal
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modal_infos');
    }
};
