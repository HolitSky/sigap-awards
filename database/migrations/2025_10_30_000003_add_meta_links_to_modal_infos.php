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
            $table->json('meta_links')->nullable()->after('show_form_options');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('modal_infos', function (Blueprint $table) {
            $table->dropColumn('meta_links');
        });
    }
};
