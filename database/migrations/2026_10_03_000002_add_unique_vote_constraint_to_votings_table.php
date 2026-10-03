<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add a unique constraint preventing double votes per siswa per election.
     *
     * siswas.has_voted_osis alone cannot guarantee one-vote-per-siswa under
     * concurrency; the DB-level unique index on (siswa_id, election_id) does.
     * NULL siswa_id values (votes tracked via pemilihs) are exempt, because
     * MySQL unique indexes allow multiple NULLs.
     */
    public function up(): void
    {
        // Clean up historical duplicates (keep the earliest vote per siswa+election)
        // so the unique index can be created without failing. Duplicate rows are
        // the data corruption caused by the race condition this index prevents.
        $duplicates = DB::table('votings')
            ->whereNotNull('siswa_id')
            ->select('siswa_id', 'election_id', DB::raw('MIN(id) as keep_id'))
            ->groupBy('siswa_id', 'election_id')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($duplicates as $duplicate) {
            DB::table('votings')
                ->where('siswa_id', $duplicate->siswa_id)
                ->where('election_id', $duplicate->election_id)
                ->where('id', '!=', $duplicate->keep_id)
                ->delete();
        }

        Schema::table('votings', function (Blueprint $table) {
            $table->unique(['siswa_id', 'election_id'], 'votings_siswa_election_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('votings', function (Blueprint $table) {
            $table->dropUnique('votings_siswa_election_unique');
        });
    }
};
