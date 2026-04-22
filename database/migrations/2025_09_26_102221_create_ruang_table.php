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
        Schema::create('ruang', function (Blueprint $table) {
            $table->id();
            $table->string('kode_ruang')->unique();
            $table->string('nama_ruang');
            $table->text('deskripsi')->nullable();
            $table->string('jenis_ruang'); // kelas, laboratorium, perpustakaan, kantor, dll
            $table->decimal('luas_ruang', 8, 2)->nullable(); // dalam m²
            $table->integer('kapasitas')->nullable();
            $table->string('lantai')->nullable();
            $table->string('gedung')->nullable();
            $table->string('kondisi')->default('baik'); // baik, rusak, renovasi
            $table->string('status')->default('aktif'); // aktif, tidak_aktif, renovasi
            $table->text('fasilitas')->nullable(); // JSON array of facilities
            $table->text('catatan')->nullable();
            $table->string('foto')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ruang');
    }
};
