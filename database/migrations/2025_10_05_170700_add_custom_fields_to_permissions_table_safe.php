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
        Schema::table('permissions', function (Blueprint $table) {
            // Add custom fields for better permission management (with safe checks)
            if (!Schema::hasColumn('permissions', 'display_name')) {
                $table->string('display_name')->nullable()->after('name');
            }

            if (!Schema::hasColumn('permissions', 'description')) {
                $table->text('description')->nullable()->after('display_name');
            }

            if (!Schema::hasColumn('permissions', 'module')) {
                $table->string('module', 100)->nullable()->after('description');
            }

            if (!Schema::hasColumn('permissions', 'action')) {
                $table->string('action', 100)->nullable()->after('module');
            }

            // Add indexes for better performance (with safe checks)
            if (!$this->indexExists('permissions', 'permissions_module_action_index')) {
                $table->index(['module', 'action'], 'permissions_module_action_index');
            }

            if (!$this->indexExists('permissions', 'permissions_display_name_index')) {
                $table->index('display_name', 'permissions_display_name_index');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            if ($this->indexExists('permissions', 'permissions_module_action_index')) {
                $table->dropIndex('permissions_module_action_index');
            }

            if ($this->indexExists('permissions', 'permissions_display_name_index')) {
                $table->dropIndex('permissions_display_name_index');
            }

            $columns = ['display_name', 'description', 'module', 'action'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('permissions', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }

    /**
     * Check if index exists
     */
    private function indexExists($table, $index)
    {
        try {
            $indexes = Schema::getConnection()->select("SHOW INDEX FROM {$table}");
            foreach ($indexes as $idx) {
                if ($idx->Key_name === $index) {
                    return true;
                }
            }
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }
};
