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
        Schema::create('card_boxes', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // h3 title
            $table->text('description'); // p description
            $table->enum('content_type', ['text_only', 'link', 'modal'])->default('text_only'); // tipe konten
            $table->string('button_text')->nullable(); // text tombol (nullable untuk text_only)
            $table->string('link_url')->nullable(); // URL link (untuk tipe link)
            $table->text('modal_content')->nullable(); // konten modal (untuk tipe modal)
            $table->integer('order')->default(0); // urutan tampilan
            $table->boolean('is_active')->default(false); // hanya 1 yang boleh aktif
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('card_boxes');
    }
};
