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
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('position')->nullable(); // Siswa, Guru, Alumni, dll
            $table->string('class')->nullable(); // Kelas untuk siswa
            $table->string('graduation_year')->nullable(); // Tahun lulus untuk alumni
            $table->text('testimonial');
            $table->integer('rating')->default(5); // Rating 1-5
            $table->string('photo')->nullable(); // Foto profil
            $table->boolean('is_approved')->default(false); // Status approval
            $table->boolean('is_featured')->default(false); // Testimonial unggulan
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
