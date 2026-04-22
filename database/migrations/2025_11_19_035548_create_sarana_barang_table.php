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
        Schema::create('sarana_barang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sarana_id')->constrained('sarana')->onDelete('cascade');
            $table->foreignId('barang_id')->constrained('barang')->onDelete('cascade');
            $table->integer('jumlah')->default(1);
            $table->string('kondisi')->default('baik'); // baik, rusak, hilang
            $table->timestamps();
            
            $table->unique(['sarana_id', 'barang_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sarana_barang');
    }
};
