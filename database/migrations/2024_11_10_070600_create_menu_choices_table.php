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
        Schema::create('menu_choices', function (Blueprint $table) {
            $table->id();
            $table->string('main_menu_title')->nullable(); // Judul main menu modal (nullable jika use_main_menu = false)
            $table->boolean('use_main_menu')->default(true); // true = pakai modal main menu, false = langsung tampil
            $table->json('menu_items')->nullable(); // Array of menu items: [{title, link, icon}]
            $table->boolean('is_active')->default(false); // Hanya 1 yang boleh aktif
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_choices');
    }
};
