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
        Schema::table('siswas', function (Blueprint $table) {
            $table->boolean('has_voted_osis')->default(false)->after('user_id');
            $table->timestamp('voted_at')->nullable()->after('has_voted_osis');
            $table->string('voting_ip')->nullable()->after('voted_at');
            $table->text('voting_user_agent')->nullable()->after('voting_ip');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            $table->dropColumn(['has_voted_osis', 'voted_at', 'voting_ip', 'voting_user_agent']);
        });
    }
};
