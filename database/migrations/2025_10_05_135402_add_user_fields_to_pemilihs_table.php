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
        Schema::table('pemilihs', function (Blueprint $table) {
            // Check if columns don't exist before adding
            if (!Schema::hasColumn('pemilihs', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id')->comment('Reference to users table');
            }
            if (!Schema::hasColumn('pemilihs', 'user_type')) {
                $table->enum('user_type', ['siswa', 'guru'])->nullable()->after('user_id')->comment('Type of user (siswa or guru)');
            }
            if (!Schema::hasColumn('pemilihs', 'email')) {
                $table->string('email')->nullable()->after('user_type')->comment('Email from user');
            }
            if (!Schema::hasColumn('pemilihs', 'nomor_hp')) {
                $table->string('nomor_hp')->nullable()->after('email')->comment('Phone number from user');
            }
            if (!Schema::hasColumn('pemilihs', 'alamat')) {
                $table->text('alamat')->nullable()->after('nomor_hp')->comment('Address from user');
            }

            // Add foreign key and index if they don't exist
            if (!Schema::hasColumn('pemilihs', 'user_id')) {
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->index(['user_id', 'user_type']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemilihs', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropIndex(['user_id', 'user_type']);
            $table->dropColumn(['user_id', 'user_type', 'email', 'nomor_hp', 'alamat']);
        });
    }
};
