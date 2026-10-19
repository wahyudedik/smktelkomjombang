<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Menambahkan kolom `show_results` ke osis_elections:
     * - true (DEFAULT, backward compatible): perolehan per calon tampil
     *   di halaman hasil SEBELUM pemilihan ditutup (perilaku existing TIDAK berubah).
     * - false: hasil per calon disembunyikan selama pemilihan masih berjalan;
     *   hanya ringkasan partisipasi anonim yang tampil. Setelah election
     *   ended/locked, hasil tampil otomatis (lihat OsisElection::resultsVisible()).
     */
    public function up(): void
    {
        Schema::table('osis_elections', function (Blueprint $table) {
            $table->boolean('show_results')->default(true)->after('allowed_classes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('osis_elections', function (Blueprint $table) {
            $table->dropColumn('show_results');
        });
    }
};
