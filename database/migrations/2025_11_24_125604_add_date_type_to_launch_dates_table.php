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
        Schema::table('launch_dates', function (Blueprint $table) {
            // Add date_type column: 'single', 'range', 'month_only', 'coming_soon'
            $table->string('date_type', 20)->default('single')->after('title');
            // Add month_year for month_only type
            $table->string('month_year', 7)->nullable()->after('end_date'); // Format: 2025-12
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('launch_dates', function (Blueprint $table) {
            $table->dropColumn(['date_type', 'month_year']);
        });
    }
};
