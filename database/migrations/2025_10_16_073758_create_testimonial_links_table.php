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
        Schema::create('testimonial_links', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Judul link (contoh: "Testimonial Alumni 2024")
            $table->string('token')->unique(); // Token unik untuk link
            $table->text('description')->nullable(); // Deskripsi link
            $table->json('target_audience'); // Array: ['Siswa', 'Guru', 'Alumni']
            $table->datetime('active_from'); // Tanggal mulai aktif
            $table->datetime('active_until'); // Tanggal berakhir
            $table->boolean('is_active')->default(true); // Status aktif
            $table->integer('max_submissions')->nullable(); // Maksimal submission (null = unlimited)
            $table->integer('current_submissions')->default(0); // Jumlah submission saat ini
            $table->string('created_by'); // Admin yang membuat link
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('testimonial_links');
    }
};
