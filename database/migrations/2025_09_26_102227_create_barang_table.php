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
        Schema::create('barang', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang')->unique();
            $table->string('nama_barang');
            $table->text('deskripsi')->nullable();
            $table->foreignId('kategori_id')->constrained('kategori_sarpras')->onDelete('cascade');
            $table->string('merk')->nullable();
            $table->string('model')->nullable();
            $table->string('serial_number')->nullable();
            $table->decimal('harga_beli', 15, 2)->nullable();
            $table->date('tanggal_pembelian')->nullable();
            $table->string('sumber_dana')->nullable();
            $table->string('kondisi')->default('baik'); // baik, rusak, hilang
            $table->string('lokasi')->nullable();
            $table->foreignId('ruang_id')->nullable()->constrained('ruang')->onDelete('set null');
            $table->string('status')->default('tersedia'); // tersedia, dipinjam, rusak, hilang
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
        Schema::dropIfExists('barang');
    }
};
