<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_excuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendance_identity_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['izin', 'sakit', 'cuti', 'dinas', 'alpha'])->comment('Jenis izin/sakit');
            $table->date('date')->comment('Tanggal izin/sakit');
            $table->text('reason')->comment('Alasan izin/sakit');
            $table->string('attachment_path')->nullable()->comment('Surat dokumen / bukti');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->comment('Status persetujuan');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete()->comment('Disetujui oleh');
            $table->timestamp('approved_at')->nullable()->comment('Waktu persetujuan');
            $table->text('rejection_reason')->nullable()->comment('Alasan penolakan');
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete()->comment('Dibuat oleh');
            $table->timestamps();

            // Unique constraint: satu identity hanya boleh punya satu excuse per hari
            $table->unique(['attendance_identity_id', 'date'], 'excuse_identity_date_unique');

            // Index untuk filter
            $table->index(['status', 'type']);
            $table->index('date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_excuses');
    }
};
