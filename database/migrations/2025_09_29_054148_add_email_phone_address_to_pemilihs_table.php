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
            $table->string('email')->nullable()->after('kelas');
            $table->string('nomor_hp')->nullable()->after('email');
            $table->text('alamat')->nullable()->after('nomor_hp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemilihs', function (Blueprint $table) {
            $table->dropColumn(['email', 'nomor_hp', 'alamat']);
        });
    }
};
