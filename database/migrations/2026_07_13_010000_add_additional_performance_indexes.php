<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Add additional performance indexes for frequently queried columns.
     *
     * These indexes target queries found in controllers that were missing
     * from the original performance indexes migration.
     */
    public function up(): void
    {
        // Pages table — used in SettingsController, PageController, LandingController
        $this->safeIndex('pages', 'pages_is_menu_index', function (Blueprint $table) {
            $table->index('is_menu');
        });

        $this->safeIndex('pages', 'pages_status_index', function (Blueprint $table) {
            $table->index('status');
        });

        $this->safeIndex('pages', 'pages_menu_position_index', function (Blueprint $table) {
            $table->index('menu_position');
        });

        $this->safeIndex('pages', 'pages_category_index', function (Blueprint $table) {
            $table->index('category');
        });

        // Barang table — used in SarprasController dashboard stats
        $this->safeIndex('barang', 'barang_kondisi_index', function (Blueprint $table) {
            $table->index('kondisi');
        });

        $this->safeIndex('barang', 'barang_is_active_index', function (Blueprint $table) {
            $table->index('is_active');
        });

        // Maintenance table — used in SarprasController
        $this->safeIndex('maintenance', 'maintenance_status_index', function (Blueprint $table) {
            $table->index('status');
        });

        // Letters table — used in LetterInController, LetterOutController
        $this->safeIndex('letters', 'letters_type_index', function (Blueprint $table) {
            $table->index('type');
        });

        $this->safeIndex('letters', 'letters_created_at_index', function (Blueprint $table) {
            $table->index('created_at');
        });

        // Testimonials table — used in TestimonialController
        $this->safeIndex('testimonials', 'testimonials_is_approved_index', function (Blueprint $table) {
            $table->index('is_approved');
        });

        // Events table — used in EventController
        $this->safeIndex('events', 'events_is_published_index', function (Blueprint $table) {
            $table->index('is_published');
        });

        // Sarana table — used in SaranaController
        $this->safeIndex('sarana', 'sarana_ruang_id_index', function (Blueprint $table) {
            $table->index('ruang_id');
        });

        // Sarana_barang pivot — composite index for value calculations
        $this->safeIndex('sarana_barang', 'sarana_barang_barang_id_index', function (Blueprint $table) {
            $table->index('barang_id');
        });

        // Audit logs — composite index for dashboard activity queries
        $this->safeIndex('audit_logs', 'audit_logs_created_action_index', function (Blueprint $table) {
            $table->index(['created_at', 'action']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $this->safeDropIndex('pages', 'pages_is_menu_index');
        $this->safeDropIndex('pages', 'pages_status_index');
        $this->safeDropIndex('pages', 'pages_menu_position_index');
        $this->safeDropIndex('pages', 'pages_category_index');
        $this->safeDropIndex('barang', 'barang_kondisi_index');
        $this->safeDropIndex('barang', 'barang_is_active_index');
        $this->safeDropIndex('maintenance', 'maintenance_status_index');
        $this->safeDropIndex('letters', 'letters_type_index');
        $this->safeDropIndex('letters', 'letters_created_at_index');
        $this->safeDropIndex('testimonials', 'testimonials_is_approved_index');
        $this->safeDropIndex('events', 'events_is_published_index');
        $this->safeDropIndex('sarana', 'sarana_ruang_id_index');
        $this->safeDropIndex('sarana_barang', 'sarana_barang_barang_id_index');
        $this->safeDropIndex('audit_logs', 'audit_logs_created_action_index');
    }

    /**
     * Safely create an index, ignoring if it already exists or table is missing.
     */
    private function safeIndex(string $table, string $indexName, callable $callback): void
    {
        try {
            if (!$this->indexExists($table, $indexName) && Schema::hasTable($table)) {
                Schema::table($table, $callback);
            }
        } catch (\Exception $e) {
            // Index might already exist or table might not exist
        }
    }

    /**
     * Safely drop an index, ignoring errors.
     */
    private function safeDropIndex(string $table, string $indexName): void
    {
        try {
            if (Schema::hasTable($table) && $this->indexExists($table, $indexName)) {
                Schema::table($table, function (Blueprint $table) use ($indexName) {
                    $table->dropIndex($indexName);
                });
            }
        } catch (\Exception $e) {
            // Index might not exist
        }
    }

    /**
     * Check if index exists (database-agnostic).
     */
    private function indexExists(string $table, string $index): bool
    {
        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            try {
                $result = DB::select("SELECT name FROM sqlite_master WHERE type='index' AND name=?", [$index]);
                return !empty($result);
            } catch (\Exception $e) {
                return false;
            }
        } elseif ($driver === 'mysql' || $driver === 'mariadb') {
            try {
                $database = DB::getDatabaseName();
                $result = DB::select(
                    "SELECT COUNT(*) as count FROM information_schema.statistics
                     WHERE table_schema = ? AND table_name = ? AND index_name = ?",
                    [$database, $table, $index]
                );
                return ($result[0]->count ?? 0) > 0;
            } catch (\Exception $e) {
                return false;
            }
        }

        return false;
    }
};
