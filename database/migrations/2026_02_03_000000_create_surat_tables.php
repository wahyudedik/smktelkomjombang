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
        // 1. Add unit_code to users if not exists
        if (!Schema::hasColumn('users', 'unit_code')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('unit_code')->nullable()->after('email');
            });
        }

        // 2. Create letter_formats table
        if (!Schema::hasTable('letter_formats')) {
            Schema::create('letter_formats', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->string('type')->default('out'); // out (surat keluar), in (surat masuk)
                $table->string('period_mode')->default('year'); // year, month, all
                $table->string('counter_scope')->default('global'); // global, unit
                $table->string('format_template')->nullable(); // Legacy support or quick view
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        } else {
            // Update existing table if needed
            Schema::table('letter_formats', function (Blueprint $table) {
                if (!Schema::hasColumn('letter_formats', 'description')) {
                    $table->text('description')->nullable()->after('name');
                }
                if (!Schema::hasColumn('letter_formats', 'type')) {
                    $table->string('type')->default('out')->after('description');
                }
                if (!Schema::hasColumn('letter_formats', 'period_mode')) {
                    $table->string('period_mode')->default('year')->after('type');
                }
                if (!Schema::hasColumn('letter_formats', 'counter_scope')) {
                    $table->string('counter_scope')->default('global')->after('period_mode');
                }
                if (Schema::hasColumn('letter_formats', 'reset_period')) {
                    $table->dropColumn('reset_period'); // Replaced by period_mode
                }
            });
        }

        // 3. Create letter_format_segments table
        if (!Schema::hasTable('letter_format_segments')) {
            Schema::create('letter_format_segments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('letter_format_id')->constrained()->cascadeOnDelete();
                $table->string('type'); // sequence, text, unit_code, day, month_roman, month_number, year, year_roman
                $table->string('value')->nullable(); // For constants
                $table->integer('padding')->nullable(); // For sequence padding
                $table->integer('order')->default(0);
                $table->timestamps();
            });
        } else {
            Schema::table('letter_format_segments', function (Blueprint $table) {
                if (!Schema::hasColumn('letter_format_segments', 'padding')) {
                    $table->integer('padding')->nullable()->after('value');
                }
            });
        }

        // 4. Create letters table
        if (!Schema::hasTable('letters')) {
            Schema::create('letters', function (Blueprint $table) {
                $table->id();
                $table->string('type'); // 'incoming', 'outgoing'

                // Outgoing specific
                $table->foreignId('letter_format_id')->nullable()->constrained()->nullOnDelete();
                $table->string('letter_number')->nullable(); // Generated number
                $table->integer('sequence_number')->nullable(); // The counter value

                // Common fields
                $table->string('reference_number')->nullable(); // Nomor surat (manual for incoming)
                $table->date('letter_date');
                $table->string('sender')->nullable(); // Pengirim (for incoming)
                $table->string('recipient')->nullable(); // Kepada (for outgoing)
                $table->string('subject'); // Perihal
                $table->text('description')->nullable(); // Ringkasan/Isi
                $table->string('file_path')->nullable(); // Scan file path
                $table->string('status')->default('draft'); // draft, sent, received, archived

                $table->foreignId('created_by')->constrained('users');
                $table->timestamps();
            });
        }

        // 5. Create letter_counters table
        if (!Schema::hasTable('letter_counters')) {
            Schema::create('letter_counters', function (Blueprint $table) {
                $table->id();
                $table->foreignId('letter_format_id')->constrained()->cascadeOnDelete();
                $table->string('scope_unit_code')->nullable(); // If counter is per unit
                $table->integer('year');
                $table->integer('month')->nullable(); // If reset per month
                $table->integer('current_value')->default(0);
                $table->timestamps();

                // Unique constraint to prevent duplicates
                $table->unique(['letter_format_id', 'scope_unit_code', 'year', 'month'], 'counter_unique');
            });
        }

        // 6. Create letter_activity_logs table
        if (!Schema::hasTable('letter_activity_logs')) {
            Schema::create('letter_activity_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('letter_id')->constrained()->cascadeOnDelete();
                $table->foreignId('user_id')->constrained();
                $table->string('action'); // created, updated, generated, uploaded
                $table->text('details')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('letter_activity_logs');
        Schema::dropIfExists('letter_counters');
        Schema::dropIfExists('letters');
        Schema::dropIfExists('letter_format_segments');
        Schema::dropIfExists('letter_formats');
    }
};
