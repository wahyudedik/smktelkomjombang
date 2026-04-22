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
        Schema::create('siswas', function (Blueprint $table) {
            $table->id();
            $table->string('nis')->unique(); // Nomor Induk Siswa
            $table->string('nisn')->unique(); // Nomor Induk Siswa Nasional
            $table->string('nama_lengkap');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->date('tanggal_lahir');
            $table->string('tempat_lahir');
            $table->text('alamat');
            $table->string('no_telepon')->nullable();
            $table->string('no_wa')->nullable();
            $table->string('email')->nullable();
            $table->string('foto')->nullable();
            $table->string('kelas')->nullable(); // Kelas saat ini
            $table->string('jurusan')->nullable(); // Jurusan untuk SMA/SMK
            $table->integer('tahun_masuk');
            $table->integer('tahun_lulus')->nullable();
            $table->enum('status', ['aktif', 'lulus', 'pindah', 'keluar', 'meninggal'])->default('aktif');
            $table->string('nama_ayah')->nullable();
            $table->string('pekerjaan_ayah')->nullable();
            $table->string('nama_ibu')->nullable();
            $table->string('pekerjaan_ibu')->nullable();
            $table->string('no_telepon_ortu')->nullable();
            $table->string('alamat_ortu')->nullable();
            $table->text('prestasi')->nullable(); // Prestasi yang diraih
            $table->text('catatan')->nullable(); // Catatan khusus
            $table->json('nilai_akademik')->nullable(); // Nilai akademik
            $table->json('ekstrakurikuler')->nullable(); // Ekstrakurikuler yang diikuti
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Link ke user account
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswas');
    }
};
