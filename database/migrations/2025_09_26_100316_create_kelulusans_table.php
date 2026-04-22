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
        Schema::create('kelulusans', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('nisn')->unique();
            $table->string('nis')->nullable();
            $table->string('jurusan')->nullable();
            $table->integer('tahun_ajaran');
            $table->enum('status', ['lulus', 'tidak_lulus', 'mengulang'])->default('lulus');
            $table->string('tempat_kuliah')->nullable();
            $table->string('tempat_kerja')->nullable();
            $table->string('jurusan_kuliah')->nullable();
            $table->string('jabatan_kerja')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('no_wa')->nullable();
            $table->text('alamat')->nullable();
            $table->text('prestasi')->nullable();
            $table->text('catatan')->nullable();
            $table->string('foto')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('tanggal_lulus')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelulusans');
    }
};
