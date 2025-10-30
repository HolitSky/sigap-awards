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
            $table->string('title', 100)->change(); // Update from 50 to 100
            $table->string('subtitle', 200)->nullable()->change(); // Update from 100 to 200
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('modal_infos', function (Blueprint $table) {
            $table->string('title', 50)->change();
            $table->string('subtitle', 100)->nullable()->change();
        });
    }
};
