<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Add new fields for Instagram Platform API
     */
    public function up(): void
    {
        Schema::table('instagram_settings', function (Blueprint $table) {
            // Instagram account information
            $table->string('username')->nullable()->after('user_id');
            $table->string('account_type')->nullable()->after('username')->comment('BUSINESS or CREATOR');

            // Token management
            $table->timestamp('token_expires_at')->nullable()->after('last_sync');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('instagram_settings', function (Blueprint $table) {
            $table->dropColumn(['username', 'account_type', 'token_expires_at']);
        });
    }
};
