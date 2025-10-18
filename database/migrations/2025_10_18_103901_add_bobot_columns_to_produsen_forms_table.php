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
        Schema::table('produsen_forms', function (Blueprint $table) {
            $table->integer('bobot')->default(45)->after('total_score');
            $table->decimal('nilai_bobot_total', 8, 2)->nullable()->after('bobot');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produsen_forms', function (Blueprint $table) {
            $table->dropColumn(['bobot', 'nilai_bobot_total']);
        });
    }
};
