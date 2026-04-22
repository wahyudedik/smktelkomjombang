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
        Schema::create('votings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('calon_id')->constrained()->onDelete('cascade');
            $table->foreignId('pemilih_id')->constrained()->onDelete('cascade');
            $table->timestamp('waktu_voting');
            $table->string('ip_address');
            $table->text('user_agent');
            $table->boolean('is_valid')->default(true);
            $table->timestamps();

            $table->unique(['calon_id', 'pemilih_id']); // Prevent duplicate votes
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('votings');
    }
};
