<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * - Change tahun_ajaran from integer to string to support "YYYY/YYYY" format
     * - Change status from enum to string for flexibility (still validated at app level)
     */
    public function up(): void
    {
        Schema::table('kelulusans', function (Blueprint $table) {
            // Change tahun_ajaran from integer to string (supports "2025/2026" format)
            $table->string('tahun_ajaran', 20)->change();

            // Change status from enum to string (validation handled at application level)
            $table->string('status', 20)->default('lulus')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kelulusans', function (Blueprint $table) {
            // Revert tahun_ajaran back to integer (data may be lost if string values exist)
            $table->integer('tahun_ajaran')->change();

            // Revert status back to enum
            $table->enum('status', ['lulus', 'tidak_lulus', 'mengulang'])->default('lulus')->change();
        });
    }
};
