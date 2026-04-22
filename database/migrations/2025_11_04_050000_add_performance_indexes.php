<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Add indexes for frequently queried columns to improve performance
     */
    public function up(): void
    {
        $driver = DB::getDriverName();

        // Users table indexes
        try {
            if (!$this->indexExists('users', 'users_email_index')) {
                Schema::table('users', function (Blueprint $table) {
                    $table->index('email');
                });
            }
        } catch (\Exception $e) {
            // Index might already exist
        }

        // Kelulusan table indexes
        try {
            if (!$this->indexExists('kelulusans', 'kelulusans_status_index')) {
                Schema::table('kelulusans', function (Blueprint $table) {
                    $table->index('status');
                });
            }
            if (!$this->indexExists('kelulusans', 'kelulusans_tahun_ajaran_index')) {
                Schema::table('kelulusans', function (Blueprint $table) {
                    $table->index('tahun_ajaran');
                });
            }
            if (!$this->indexExists('kelulusans', 'kelulusans_nisn_index')) {
                Schema::table('kelulusans', function (Blueprint $table) {
                    $table->index('nisn');
                });
            }
            if (!$this->indexExists('kelulusans', 'kelulusans_nis_index')) {
                Schema::table('kelulusans', function (Blueprint $table) {
                    $table->index('nis');
                });
            }
        } catch (\Exception $e) {
            // Indexes might already exist
        }

        // Siswa table indexes
        try {
            if (!$this->indexExists('siswas', 'siswas_status_index')) {
                Schema::table('siswas', function (Blueprint $table) {
                    $table->index('status');
                });
            }
            if (!$this->indexExists('siswas', 'siswas_kelas_index')) {
                Schema::table('siswas', function (Blueprint $table) {
                    $table->index('kelas');
                });
            }
        } catch (\Exception $e) {
            // Indexes might already exist
        }

        // Calon table indexes
        try {
            if (!$this->indexExists('calons', 'calons_is_active_index')) {
                Schema::table('calons', function (Blueprint $table) {
                    $table->index('is_active');
                });
            }
        } catch (\Exception $e) {
            // Index might already exist
        }

        // Pemilih table indexes
        try {
            if (!$this->indexExists('pemilihs', 'pemilihs_is_active_index')) {
                Schema::table('pemilihs', function (Blueprint $table) {
                    $table->index('is_active');
                });
            }
        } catch (\Exception $e) {
            // Index might already exist
        }

        // Audit logs indexes
        try {
            if (!$this->indexExists('audit_logs', 'audit_logs_created_at_index')) {
                Schema::table('audit_logs', function (Blueprint $table) {
                    $table->index('created_at');
                });
            }
            if (!$this->indexExists('audit_logs', 'audit_logs_action_index')) {
                Schema::table('audit_logs', function (Blueprint $table) {
                    $table->index('action');
                });
            }
        } catch (\Exception $e) {
            // Indexes might already exist
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Note: In production, you might want to keep indexes for performance
        // Only drop if really necessary for rollback
        try {
            Schema::table('users', function (Blueprint $table) {
                $table->dropIndex('users_email_index');
            });
        } catch (\Exception $e) {
            // Index might not exist
        }

        try {
            Schema::table('kelulusans', function (Blueprint $table) {
                $table->dropIndex('kelulusans_status_index');
                $table->dropIndex('kelulusans_tahun_ajaran_index');
                $table->dropIndex('kelulusans_nisn_index');
                $table->dropIndex('kelulusans_nis_index');
            });
        } catch (\Exception $e) {
            // Indexes might not exist
        }

        try {
            Schema::table('siswas', function (Blueprint $table) {
                $table->dropIndex('siswas_status_index');
                $table->dropIndex('siswas_kelas_index');
            });
        } catch (\Exception $e) {
            // Indexes might not exist
        }

        try {
            Schema::table('calons', function (Blueprint $table) {
                $table->dropIndex('calons_is_active_index');
            });
        } catch (\Exception $e) {
            // Index might not exist
        }

        try {
            Schema::table('pemilihs', function (Blueprint $table) {
                $table->dropIndex('pemilihs_is_active_index');
            });
        } catch (\Exception $e) {
            // Index might not exist
        }

        try {
            Schema::table('audit_logs', function (Blueprint $table) {
                $table->dropIndex('audit_logs_created_at_index');
                $table->dropIndex('audit_logs_action_index');
            });
        } catch (\Exception $e) {
            // Indexes might not exist
        }
    }

    /**
     * Check if index exists (database-agnostic)
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
