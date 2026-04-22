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
        Schema::table('instagram_settings', function (Blueprint $table) {
            $table->string('webhook_verify_token')->nullable()->after('redirect_uri')
                ->comment('Webhook verification token for Meta callbacks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('instagram_settings', function (Blueprint $table) {
            $table->dropColumn('webhook_verify_token');
        });
    }
};
