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
        Schema::create('async_jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['import', 'export']);
            $table->string('module'); // users, siswa, guru, barang, calon, pemilih, kelulusan
            $table->enum('status', ['pending', 'running', 'completed', 'failed'])->default('pending');
            $table->json('payload')->nullable(); // Original request data (filters, options)
            $table->json('result')->nullable(); // Result data (row count, file path, etc.)
            $table->text('error_message')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->timestamps();

            // Indexes for performance
            $table->index('status');
            $table->index(['user_id', 'status']);
            $table->index(['type', 'status']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('async_jobs');
    }
};
