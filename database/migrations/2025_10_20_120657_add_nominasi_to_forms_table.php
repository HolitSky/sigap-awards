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
        // Add nominasi column to bpkh_forms table
        Schema::table('bpkh_forms', function (Blueprint $table) {
            $table->boolean('nominasi')->default(false)->after('petugas_bpkh');
        });

        // Add nominasi column to produsen_forms table
        Schema::table('produsen_forms', function (Blueprint $table) {
            $table->boolean('nominasi')->default(false)->after('nama_petugas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove nominasi column from bpkh_forms table
        Schema::table('bpkh_forms', function (Blueprint $table) {
            $table->dropColumn('nominasi');
        });

        // Remove nominasi column from produsen_forms table
        Schema::table('produsen_forms', function (Blueprint $table) {
            $table->dropColumn('nominasi');
        });
    }
};
