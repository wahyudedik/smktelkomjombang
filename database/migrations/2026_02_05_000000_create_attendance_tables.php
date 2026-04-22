<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_devices', function (Blueprint $table) {
            $table->id();
            $table->string('serial_number')->unique();
            $table->string('name')->nullable();
            $table->string('ip_address')->nullable();
            $table->unsignedInteger('port')->nullable();
            $table->string('comm_key')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_seen_at')->nullable();
            $table->timestamp('last_processed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('attendance_identities', function (Blueprint $table) {
            $table->id();
            $table->string('kind');
            $table->foreignId('guru_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('siswa_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('device_pin')->index();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['kind', 'guru_id', 'siswa_id', 'user_id'], 'attendance_identity_unique');
        });

        Schema::create('attendance_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendance_device_id')->constrained()->cascadeOnDelete();
            $table->string('device_pin')->index();
            $table->timestamp('log_time');
            $table->string('verify_mode')->nullable();
            $table->string('in_out_mode')->nullable();
            $table->text('raw')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->unique(['attendance_device_id', 'device_pin', 'log_time'], 'attendance_log_dedupe');
        });

        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendance_identity_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->timestamp('first_in_at')->nullable();
            $table->timestamp('last_out_at')->nullable();
            $table->string('status')->default('present');
            $table->timestamps();

            $table->unique(['attendance_identity_id', 'date'], 'attendance_daily_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
        Schema::dropIfExists('attendance_logs');
        Schema::dropIfExists('attendance_identities');
        Schema::dropIfExists('attendance_devices');
    }
};
