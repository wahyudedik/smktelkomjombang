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
        Schema::create('gurus', function (Blueprint $table) {
            $table->id();
            $table->string('nip')->unique(); // Nomor Induk Pegawai
            $table->string('nama_lengkap');
            $table->string('gelar_depan')->nullable(); // Dr., Prof., dll
            $table->string('gelar_belakang')->nullable(); // S.Pd., M.Pd., dll
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->date('tanggal_lahir');
            $table->string('tempat_lahir');
            $table->text('alamat');
            $table->string('no_telepon')->nullable();
            $table->string('no_wa')->nullable();
            $table->string('email')->nullable();
            $table->string('foto')->nullable();
            $table->enum('status_kepegawaian', ['PNS', 'CPNS', 'GTT', 'GTY', 'Honorer']);
            $table->string('jabatan')->nullable(); // Kepala Sekolah, Wakil Kepala, dll
            $table->date('tanggal_masuk');
            $table->date('tanggal_keluar')->nullable();
            $table->enum('status_aktif', ['aktif', 'tidak_aktif', 'pensiun', 'meninggal'])->default('aktif');
            $table->text('pendidikan_terakhir');
            $table->string('universitas');
            $table->string('tahun_lulus');
            $table->text('sertifikasi')->nullable(); // Sertifikasi profesi
            $table->json('mata_pelajaran'); // Array mata pelajaran yang diajar
            $table->json('jadwal_mengajar')->nullable(); // Jadwal mengajar
            $table->text('prestasi')->nullable(); // Prestasi yang diraih
            $table->text('catatan')->nullable(); // Catatan khusus
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Link ke user account
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gurus');
    }
};
