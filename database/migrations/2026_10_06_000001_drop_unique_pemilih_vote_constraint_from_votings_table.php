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
     * ⚠️ FK INDEX DEPENDENCY — MariaDB/MySQL Error 1553:
     * Index composite UNIQUE `votings_pemilih_election_unique` (pemilih_id, election_id)
     * sedang dipakai sebagai supporting index oleh FK constraint pada `pemilih_id`
     * (kemungkinan `votings_pemilih_id_foreign` → `pemilihs.id`). MariaDB/MySQL MELARANG
     * drop index yang dipakai FK constraint:
     *   "Cannot drop index 'votings_pemilih_election_unique': needed in a foreign key constraint"
     *
     * Standar workaround: sebelum drop index composite, buat single-column index
     * `votings_pemilih_id_index` (kolom `pemilih_id` saja) sebagai supporting index
     * ALTERNATIF untuk FK → drop baru diizinkan oleh MariaDB/MySQL.
     *
     * Nama index aktual (TERVERIFIKASI dari migration 2026_10_03_000003):
     * `votings_pemilih_election_unique` — nama eksplisit, bukan default Laravel.
     *
     * Check defensif (idempotent, aman di-run ulang):
     * (1) MySQL/MariaDB → information_schema.STATISTICS mencari index UNIQUE aktual
     *     yang mencakup kolom (pemilih_id, election_id) apa pun namanya.
     * (2) Fallback → Schema::hasIndex() by nama eksplisit sebelum dropUnique —
     *     aman untuk SQLite (environment test) dan MySQL.
     */
    public function up(): void
    {
        $compositeIndexes = $this->findUniquePemilihVoteIndexes();

        if (empty($compositeIndexes)) {
            // Composite unique index sudah tidak ada (sudah pernah di-drop / belum
            // pernah dibuat) — idempotent, tidak ada yang perlu dilakukan.
            return;
        }

        // Step 1: Buat supporting index alternatif untuk FK constraint jika belum ada.
        // FK pada `pemilih_id` membutuhkan index sebagai supporting index. Tanpa index
        // ini, DROP INDEX composite DIBLOKIR oleh MariaDB/MySQL (Error 1553).
        // Lihat komentar FK INDEX DEPENDENCY di atas.
        if (!$this->hasSingleColumnPemilihIdIndex()) {
            Schema::table('votings', function (Blueprint $table) {
                $table->index('pemilih_id', 'votings_pemilih_id_index');
            });
        }

        // Step 2: Drop composite unique index (FK sekarang punya supporting index alternatif).
        foreach ($compositeIndexes as $indexName) {
            Schema::table('votings', function (Blueprint $table) use ($indexName) {
                $table->dropUnique($indexName);
            });
        }
    }

    /**
     * Reverse the migrations: re-create unique index (pemilih_id, election_id)
     * dengan nama eksplisit yang sama dengan migration 2026_10_03_000003.
     *
     * Idempotent & defensif:
     * (1) Guard duplikat (pemilih_id, election_id) non-null — SKIP recreate jika ada
     *     duplikat demi integritas data (multi-vote guru sudah tercatat).
     * (2) Recreate composite unique index jika belum ada.
     * (3) Drop helper index `votings_pemilih_id_index` (bersih-bersih — index ini
     *     hanya dibuat sebagai supporting index sementara saat drop composite di up()).
     */
    public function down(): void
    {
        if (!Schema::hasIndex('votings', 'votings_pemilih_election_unique')) {
            // Pembersihan duplikat defensif: unique hanya bisa dibuat ulang bila tidak ada
            // pasangan (pemilih_id, election_id) non-null yang duplikat.
            $hasDuplicates = DB::table('votings')
                ->whereNotNull('pemilih_id')
                ->select('pemilih_id', 'election_id')
                ->groupBy('pemilih_id', 'election_id')
                ->havingRaw('COUNT(*) > 1')
                ->exists();

            if (!$hasDuplicates) {
                Schema::table('votings', function (Blueprint $table) {
                    $table->unique(['pemilih_id', 'election_id'], 'votings_pemilih_election_unique');
                });
            }
            // Jika ada duplikat → SKIP recreate agar rollback tidak merusak data voting.
        }

        // Bersih-bersih: drop helper index yang hanya dibuat sebagai supporting index
        // alternatif untuk FK saat drop composite di up().
        if (Schema::hasIndex('votings', 'votings_pemilih_id_index')) {
            Schema::table('votings', function (Blueprint $table) {
                $table->dropIndex('votings_pemilih_id_index');
            });
        }
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

    /**
     * Cek apakah index single-column pada kolom `pemilih_id` saja sudah ada.
     *
     * Pola defensif dua lapis:
     * (1) MySQL/MariaDB → information_schema.STATISTICS: index yang kolomnya HANYA
     *     `pemilih_id` (GROUP BY index_name HAVING SUM(column_name='pemilih_id')>0
     *     AND COUNT(*)=1) — apa pun nama index-nya.
     * (2) Fallback → Schema::hasIndex('votings', 'votings_pemilih_id_index') by nama
     *     eksplisit — mendukung MySQL & SQLite.
     */
    private function hasSingleColumnPemilihIdIndex(): bool
    {
        $driver = Schema::getConnection()->getDriverName();

        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            try {
                $rows = DB::select(
                    "SELECT index_name
                     FROM information_schema.STATISTICS
                     WHERE table_schema = DATABASE()
                       AND table_name = 'votings'
                     GROUP BY index_name
                     HAVING SUM(column_name = 'pemilih_id') > 0
                        AND COUNT(*) = 1"
                );

                if (!empty($rows)) {
                    return true;
                }
            } catch (\Throwable $e) {
                // Lanjut ke fallback by nama di bawah.
            }
        }

        // (2) Fallback by nama eksplisit — Schema::hasIndex mendukung MySQL & SQLite.
        return Schema::hasIndex('votings', 'votings_pemilih_id_index');
    }
};
