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
        Schema::create('calons', function (Blueprint $table) {
            $table->id();
            $table->string('nama_ketua');
            $table->string('foto_ketua')->nullable();
            $table->string('nama_wakil');
            $table->string('foto_wakil')->nullable();
            $table->text('visi_misi');
            $table->enum('jenis_pencalonan', ['ketua', 'wakil', 'pasangan'])->default('pasangan');
            $table->text('program_kerja')->nullable();
            $table->text('motivasi')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calons');
    }
};
