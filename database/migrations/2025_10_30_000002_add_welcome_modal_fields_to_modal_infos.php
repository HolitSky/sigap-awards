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
        Schema::table('modal_infos', function (Blueprint $table) {
            $table->enum('modal_type', ['reminder', 'welcome'])->default('reminder')->after('id');
            $table->text('intro_text')->nullable()->after('subtitle'); // Welcome modal intro
            $table->text('footer_text')->nullable()->after('intro_text'); // Welcome modal footer
            $table->boolean('show_form_options')->default(false)->after('footer_text'); // Show/hide form options
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('modal_infos', function (Blueprint $table) {
            $table->dropColumn(['modal_type', 'intro_text', 'footer_text', 'show_form_options']);
        });
    }
};
