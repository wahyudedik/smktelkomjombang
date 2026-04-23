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
        Schema::table('partners', function (Blueprint $table) {
            if (!Schema::hasColumn('partners', 'name')) {
                $table->string('name')->after('id');
            }
            if (!Schema::hasColumn('partners', 'description')) {
                $table->text('description')->nullable()->after('name');
            }
            if (!Schema::hasColumn('partners', 'logo')) {
                $table->string('logo')->nullable()->after('description');
            }
            if (!Schema::hasColumn('partners', 'website')) {
                $table->string('website')->nullable()->after('logo');
            }
            if (!Schema::hasColumn('partners', 'category')) {
                $table->string('category')->nullable()->after('website');
            }
            if (!Schema::hasColumn('partners', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('category');
            }
            if (!Schema::hasColumn('partners', 'sort_order')) {
                $table->integer('sort_order')->default(0)->after('is_active');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('partners', function (Blueprint $table) {
            $table->dropColumn(['name', 'description', 'logo', 'website', 'category', 'is_active', 'sort_order']);
        });
    }
};
