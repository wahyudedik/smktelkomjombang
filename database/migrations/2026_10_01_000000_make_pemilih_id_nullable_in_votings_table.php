<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Voting can be recorded via siswa_id (dual-tracking), so pemilih_id
     * must be nullable to allow votes without a pemilihs record.
     */
    public function up(): void
    {
        Schema::table('votings', function (Blueprint $table) {
            $table->foreignId('pemilih_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('votings', function (Blueprint $table) {
            $table->foreignId('pemilih_id')->nullable(false)->change();
        });
    }
};
