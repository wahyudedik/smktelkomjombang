<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah kolom `user_type` ke tabel `calons`.
     *
     * Latar belakang: form create/edit calon kini mendukung 3 mode sumber data
     * (Siswa / Guru / Manual). Kolom ini HANYA penanda/label asal calon untuk
     * keperluan tampilan (badge di index/show) — TIDAK mengubah query voting
     * atau logika apa pun.
     *
     * Nilai: 'siswa' | 'guru' | NULL.
     * NULL = legacy (data lama) ATAU mode Manual (nama teks bebas) — sengaja
     * nullable agar data lama tidak terdampak dan form manual tetap mandiri
     * (nama tidak harus ada di tabel siswas/gurus).
     *
     * Idempotent guard (`Schema::hasColumn`) → aman di-run ulang, aman untuk
     * MySQL/MariaDB production DAN SQLite in-memory test environment.
     */
    public function up(): void
    {
        if (Schema::hasColumn('calons', 'user_type')) {
            // Kolom sudah ada (migration pernah dijalankan / ditambahkan manual) — skip.
            return;
        }

        Schema::table('calons', function (Blueprint $table) {
            $table->enum('user_type', ['siswa', 'guru'])
                ->nullable()
                ->after('jenis_kelamin')
                ->comment('Penanda asal calon (siswa/guru); NULL = legacy/manual');
        });
    }

    /**
     * Reverse the migrations: drop kolom `user_type`.
     * Idempotent guard untuk menghindari error bila kolom sudah tidak ada.
     */
    public function down(): void
    {
        if (!Schema::hasColumn('calons', 'user_type')) {
            return;
        }

        Schema::table('calons', function (Blueprint $table) {
            $table->dropColumn('user_type');
        });
    }
};
