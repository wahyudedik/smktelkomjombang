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
        Schema::create('maintenance', function (Blueprint $table) {
            $table->id();
            $table->string('kode_maintenance')->unique();
            $table->string('jenis_item'); // barang, ruang
            $table->foreignId('item_id'); // ID of barang or ruang
            $table->string('jenis_maintenance'); // rutin, perbaikan, pembersihan, inspeksi
            $table->text('deskripsi_masalah')->nullable();
            $table->text('tindakan_perbaikan')->nullable();
            $table->date('tanggal_maintenance');
            $table->date('tanggal_selesai')->nullable();
            $table->string('status')->default('dijadwalkan'); // dijadwalkan, sedang_dikerjakan, selesai, dibatalkan
            $table->decimal('biaya', 15, 2)->nullable();
            $table->string('teknisi')->nullable();
            $table->text('catatan')->nullable();
            $table->string('foto_sebelum')->nullable();
            $table->string('foto_sesudah')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance');
    }
};
