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
        Schema::table('pages', function (Blueprint $table) {
            // Menu fields
            $table->boolean('is_menu')->default(false)->after('is_featured');
            $table->string('menu_title')->nullable()->after('is_menu');
            $table->string('menu_position')->default('header')->after('menu_title'); // header, footer
            $table->integer('parent_id')->nullable()->after('menu_position'); // for submenu
            $table->string('menu_icon')->nullable()->after('parent_id');
            $table->string('menu_url')->nullable()->after('menu_icon'); // custom URL instead of page slug
            $table->boolean('menu_target_blank')->default(false)->after('menu_url'); // open in new tab
            $table->integer('menu_sort_order')->default(0)->after('menu_target_blank');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn([
                'is_menu',
                'menu_title',
                'menu_position',
                'parent_id',
                'menu_icon',
                'menu_url',
                'menu_target_blank',
                'menu_sort_order'
            ]);
        });
    }
};
