<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_commands', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendance_device_id')->constrained()->cascadeOnDelete();
            $table->string('kind');
            $table->string('device_pin')->nullable()->index();
            $table->text('command');
            $table->string('status')->default('pending')->index();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('executed_at')->nullable();
            $table->string('result_code')->nullable();
            $table->text('result_raw')->nullable();
            $table->timestamps();

            $table->index(['attendance_device_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_commands');
    }
};
