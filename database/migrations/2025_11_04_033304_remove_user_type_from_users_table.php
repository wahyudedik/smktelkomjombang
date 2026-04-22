<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Remove user_type column - now using Spatie Permission roles only
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Check if column exists before trying to drop
            if (Schema::hasColumn('users', 'user_type')) {
                // Drop index first if it exists - try common index names
                // Using DB::statement to avoid exception if index doesn't exist
                try {
                    DB::statement('ALTER TABLE `users` DROP INDEX `users_user_type_index`');
                } catch (\Exception $e) {
                    // Index might not exist with this name, try alternative
                    try {
                        DB::statement('ALTER TABLE `users` DROP INDEX `user_type`');
                    } catch (\Exception $e2) {
                        // Index might not exist at all, that's fine
                    }
                }
                
                // Drop the column
                $table->dropColumn('user_type');
            }
        });
    }

    /**
     * Reverse the migrations.
     * Restore user_type column for rollback
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('user_type', 50)->default('siswa')->after('password');
            $table->index('user_type');
        });
    }
};
