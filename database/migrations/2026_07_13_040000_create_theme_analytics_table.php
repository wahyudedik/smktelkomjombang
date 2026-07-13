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
        Schema::create('theme_analytics', function (Blueprint $table) {
            $table->id();
            $table->string('theme', 50)->index();
            $table->date('date')->index();
            $table->unsignedInteger('page_views')->default(0);
            $table->unsignedInteger('unique_visitors')->default(0);
            $table->float('avg_time_on_page')->default(0); // seconds
            $table->float('bounce_rate')->default(0); // percentage
            $table->timestamps();

            // Composite unique constraint: one row per theme per day
            $table->unique(['theme', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('theme_analytics');
    }
};
