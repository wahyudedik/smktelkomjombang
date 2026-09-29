<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds `theme` column to pages table for per-theme menu management.
     * - null = global (applies to all themes)
     * - 'telkom' = only for telkom theme
     * - 'maudu' = only for maudu theme
     * - etc. for future themes
     */
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->string('theme')->nullable()->after('menu_position')
                ->comment('Associated theme: null=global, telkom, maudu, etc.');
            $table->index(['theme', 'is_menu', 'menu_position']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropIndex(['theme', 'is_menu', 'menu_position']);
            $table->dropColumn('theme');
        });
    }
};
