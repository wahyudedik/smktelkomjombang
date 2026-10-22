<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah kolom `overtime_minutes` ke tabel `attendances`.
     *
     * Latar belakang: logika hitungan lembur di `AttendanceSync::handle()` menghitung
     * `$overtimeMinutes` (SATUAN MENIT, via `diffInMinutes` antara `last_out_at` dan
     * `overtime_start`) tetapi hasilnya hanya ditulis ke log — tidak pernah persist.
     * Kolom ini menyimpan hasil hitungan tersebut agar lembur tidak "hilang".
     *
     * Default 0 = tidak ada lembur (juga berlaku saat `overtime_enabled` = false).
     *
     * Idempotent guard (`Schema::hasColumn`) → aman di-run ulang, aman untuk
     * MySQL/MariaDB production DAN SQLite in-memory test environment.
     */
    public function up(): void
    {
        if (Schema::hasColumn('attendances', 'overtime_minutes')) {
            // Kolom sudah ada (migration pernah dijalankan / ditambahkan manual) — skip.
            return;
        }

        Schema::table('attendances', function (Blueprint $table) {
            $table->unsignedInteger('overtime_minutes')->default(0)->after('status');
        });
    }

    /**
     * Reverse the migrations: drop kolom `overtime_minutes`.
     * Idempotent guard untuk menghindari error bila kolom sudah tidak ada.
     */
    public function down(): void
    {
        if (!Schema::hasColumn('attendances', 'overtime_minutes')) {
            return;
        }

        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn('overtime_minutes');
        });
    }
};
