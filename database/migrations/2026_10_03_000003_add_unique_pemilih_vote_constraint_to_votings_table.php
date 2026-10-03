<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('votings', function (Blueprint $table) {
            // Unique untuk vote guru (pemilih_id terisi, siswa_id null)
            // MySQL unique constraint meng-exempt NULL values, jadi aman untuk data lama
            $table->unique(['pemilih_id', 'election_id'], 'votings_pemilih_election_unique');
        });
    }

    public function down(): void
    {
        Schema::table('votings', function (Blueprint $table) {
            $table->dropUnique('votings_pemilih_election_unique');
        });
    }
};
