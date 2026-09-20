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
        Schema::create('guest_books', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique();
            $table->string('guest_name', 255);
            $table->string('nik', 20)->nullable();
            $table->text('address')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('organization', 255)->nullable();
            $table->string('position', 255)->nullable();
            $table->string('visit_category', 50);
            $table->text('visit_purpose')->nullable();
            $table->string('visit_target', 255)->nullable();
            $table->string('photo_path', 255)->nullable();
            $table->string('vehicle_type', 50)->nullable();
            $table->string('vehicle_plate', 20)->nullable();
            $table->string('status', 20)->default('check_in');
            $table->timestamp('check_in_at');
            $table->timestamp('check_out_at')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('status');
            $table->index('check_in_at');
            $table->index('guest_name');
            $table->index('organization');
            $table->index(['status', 'check_in_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guest_books');
    }
};
