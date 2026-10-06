<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Drop unique constraint (pemilih_id, election_id) di tabel `votings`.
     *
     * Aturan bisnis baru: GURU boleh memilih MAKSIMAL 2 pasangan calon dalam satu
     * pemungutan suara (multi-select). Satu submit guru menghasilkan 1–2 baris Voting
     * dengan pemilih_id yang sama — unique constraint (pemilih_id, election_id) dari
     * migration 2026_10_03_000003 memblokir skema ini dan harus di-drop.
     *
     * Constraint (siswa_id, election_id) dari migration 2026_10_03_000002 TETAP
     * dipertahankan: satu siswa tetap satu vote per election.
     *
     * Nama index aktual (TERVERIFIKASI dari migration 2026_10_03_000003):
     * `votings_pemilih_election_unique` — nama eksplisit, bukan default Laravel.
     *
     * Check defensif (idempotent):
     * (1) MySQL/MariaDB → information_schema.STATISTICS mencari index UNIQUE aktual
     *     yang mencakup kolom (pemilih_id, election_id) apa pun namanya.
     * (2) Fallback → Schema::hasIndex() by nama eksplisit sebelum dropUnique —
     *     aman untuk SQLite (environment test) dan MySQL.
     */
    public function up(): void
    {
        foreach ($this->findUniquePemilihVoteIndexes() as $indexName) {
            Schema::table('votings', function (Blueprint $table) use ($indexName) {
                $table->dropUnique($indexName);
            });
        }
    }

    /**
     * Reverse the migrations: re-create unique index (pemilih_id, election_id)
     * dengan nama eksplisit yang sama dengan migration 2026_10_03_000003.
     *
     * Defensif: jika data vote guru multi-pilihan (2 baris per election) sudah ada,
     * re-create unique akan gagal — dalam kasus itu SKIP (jangan menghapus data vote
     * yang sah).
     */
    public function down(): void
    {
        if (Schema::hasIndex('votings', 'votings_pemilih_election_unique')) {
            return; // index sudah ada — tidak perlu re-create
        }

        // Pembersihan duplikat defensif: unique hanya bisa dibuat ulang bila tidak ada
        // pasangan (pemilih_id, election_id) non-null yang duplikat.
        $hasDuplicates = DB::table('votings')
            ->whereNotNull('pemilih_id')
            ->select('pemilih_id', 'election_id')
            ->groupBy('pemilih_id', 'election_id')
            ->havingRaw('COUNT(*) > 1')
            ->exists();

        if ($hasDuplicates) {
            // Multi-vote guru sudah tercatat — unique lama tidak bisa dipulihkan tanpa
            // menghapus data. Skip agar rollback tidak merusak data voting.
            return;
        }

        Schema::table('votings', function (Blueprint $table) {
            $table->unique(['pemilih_id', 'election_id'], 'votings_pemilih_election_unique');
        });
    }

    /**
     * Cari nama index UNIQUE di `votings` yang mencakup kolom (pemilih_id, election_id).
     *
     * @return array<int, string>
     */
    private function findUniquePemilihVoteIndexes(): array
    {
        $found = [];

        // (1) MySQL/MariaDB: cari nama index unique AKTUAL via information_schema —
        //     jangan asumsikan nama (bisa berbeda bila index dibuat manual/versi lain).
        $driver = Schema::getConnection()->getDriverName();

        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            try {
                $rows = DB::select(
                    "SELECT DISTINCT index_name
                     FROM information_schema.STATISTICS
                     WHERE table_schema = DATABASE()
                       AND table_name = 'votings'
                       AND non_unique = 0
                     GROUP BY index_name
                     HAVING SUM(column_name = 'pemilih_id') > 0
                        AND SUM(column_name = 'election_id') > 0"
                );

                foreach ($rows as $row) {
                    $found[] = (string) $row->index_name;
                }
            } catch (\Throwable $e) {
                // Lanjut ke fallback by nama eksplisit di bawah.
            }
        }

        // (2) Fallback by nama eksplisit (terverifikasi dari migration 2026_10_03_000003:
        //     $table->unique(['pemilih_id', 'election_id'], 'votings_pemilih_election_unique')).
        //     Schema::hasIndex SEBELUM dropUnique → defensif/idempotent.
        //     Mendukung MySQL & SQLite (SQLiteGrammar::compileIndexes tersedia di Laravel 12).
        if (!in_array('votings_pemilih_election_unique', $found, true)
            && Schema::hasIndex('votings', 'votings_pemilih_election_unique')) {
            $found[] = 'votings_pemilih_election_unique';
        }

        return array_values(array_unique($found));
    }
};
