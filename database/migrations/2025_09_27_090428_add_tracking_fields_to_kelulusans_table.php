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
        Schema::table('kelulusans', function (Blueprint $table) {
            $table->foreignId('siswa_id')->nullable()->after('id')->constrained('siswas')->onDelete('set null');
            $table->integer('check_count')->default(0)->after('tanggal_lulus');
            $table->timestamp('last_checked_at')->nullable()->after('check_count');
            $table->string('check_ip')->nullable()->after('last_checked_at');
            $table->text('check_user_agent')->nullable()->after('check_ip');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kelulusans', function (Blueprint $table) {
            $table->dropForeign(['siswa_id']);
            $table->dropColumn([
                'siswa_id',
                'check_count',
                'last_checked_at',
                'check_ip',
                'check_user_agent'
            ]);
        });
    }
};
